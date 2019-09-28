<?php
namespace Application\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;
use DBAL\Entity\Movimiento;
use DBAL\Entity\MotivoMovimiento;

class MovimientosManager {
    
    private $entityManager;
    private $catalogoManager;

    /**
     * Constructor del Servicio
     */
    public function __construct($entityManager, $catalogoManager) 
    {
        $this->entityManager = $entityManager;
        $this->catalogoManager = $catalogoManager;
    }

    public function procesarArchivoExcel($archivo){
        $spreadsheet = IOFactory::load($archivo);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        foreach ($sheetData as $filaDatos){
            $this->procesarFilasArchivo($filaDatos);
        }
    }

    /**
     * Un reemplazo sin sentido, es agregar el simbolo '@' en el final de la cadena.
     *
     * @param [type] $cadena
     * @return void
     */
    private function controlReemplazoSinSentido($cadena){
        $arrString = explode('@', $cadena);

        if ($arrString[1] != ''){
            return false;
        }

        return true;
    }

    /**
     * Procesa la descripcion del movimiento que viene en el archivo.
     * Lo ideal seria separarlo en dos: MotivoMovimiento y Detalle del Movimiento.
     * 
     * En algunos casos, solo viene con MotivoMovimiento y en otro se fuerza la separación
     * detectando con qué tarjeta se hizo el movimiento.
     *
     * @param [String] $detalle
     * @return array
     */
    private function procesarDetalleMovimientoDesdeArchivo($detalle){
        $nuevoString = '';
        
        if (preg_match("/\s\s+/", $detalle)) {
            //Si tiene mas de dos espacios consecutivos, entonces es 
            //MotivoMovimiento - Detalle del Movimiento (Local o tarjeta)
            
            //Reemplazo los espacios por un caracter que pueda identificar 
            //luego para hacer la separacion del string
            $nuevoString = preg_replace("/\s\s+/", '@', $detalle, 1);
        }

        if (($nuevoString == '' || $this->controlReemplazoSinSentido($nuevoString)) && strpos($detalle, 'TARJ') !== false){
            //Si no tiene dos espacios consecutivos y si tiene la palabra TARJ
            //Entonces separo el texto en ese lugar
            $nuevoString = str_replace('TARJ', '@TARJ', $detalle);
        }

        //Divido el arreglo para que quede:
        //[0] => Descripcion del motivo del movimiento
        //[1] => Detalle del movimiento
        //[2] => '' --> Ocasionalmente
        $arrStrings = explode('@', $nuevoString);
        return $arrStrings;
    }

    /**
     * Como esta compuesta la fila de datos?
     * 'A' => Fecha del movimiento
     * 'B' => Sucursal del banco
     * 'C' => Detalle del movimiento (dividir en dos partes)
     * 'D' => Un codigo
     * 'E' => Monto del movimiento
     *
     * @param [array] $filaDatos
     * @return void
     */
    private function procesarFilasArchivo($filaDatos){
        $fechaOriginal = $filaDatos['A'];
        $detalle = $filaDatos['C'];
        $valor = $filaDatos['E'];
        //Procesamiento del detalle del movimiento
        $arrStrings = $this->procesarDetalleMovimientoDesdeArchivo($detalle);

        if (count($arrStrings) > 1){
            $motivoMovimiento = $this->catalogoManager->getMotivoMovimientoPorDescripcion($arrStrings[0]);

            if (!isset($motivoMovimiento)){
                $motivoMovimiento = $this->nuevoMotivoMovimiento($arrStrings[0]);
            }

            $formato = 'd/m/Y H:i';
            $fecha = \DateTime::createFromFormat($formato, $fechaOriginal);

            if (!$this->existeMovimientoCargado($fecha, $motivoMovimiento, $arrStrings[1], $valor)){
                $this->nuevoMovimiento($fecha, $motivoMovimiento, $arrStrings[1], $valor);
            }
        }else{
            //Fallo al reconocer el movimiento
            return null;
        }
    }

    /**
     * Esta funcion determina si un movimiento es igual a los datos
     * de uno que se está por cargar. Sirve para que no se carguen dos veces
     * los mismos movimientos cuando se hace una importacion.
     * 
     * No se compran las fechas, porque anteriormente se recuperaron todos los
     * movimientos registrados en esa fecha.
     *
     * @param [Movimiento] $Movimiento
     * @param [MotivoMovimiento] $MotivoMovimiento
     * @param [String] $descripcion
     * @param [String] $valor
     * @return boolean
     */
    private function esElMismoMovimiento($Movimiento, $MotivoMovimiento, $descripcion, $valor){
        if ($Movimiento->getValor() != $valor){
            return false;
        }

        if ($Movimiento->getMotivoMovimiento()->getId() != $MotivoMovimiento->getId()){
            return false;
        }

        if ($Movimiento->getDescripcion() != trim($descripcion)){
            return false;
        }
        
        return true;
    }

    /**
     * Funcion para determinar si los datos del movimiento que se esta por guardar
     * en la base, no se encuentran previamente almacenados ya.
     * 
     * Comienza recuperando todos los movimientos de la base que tengan la misma fecha.
     *
     * @param [datetime] $fecha
     * @param [MotivoMovimiento] $motivoMovimiento
     * @param [String] $descripcion
     * @param [String] $valor
     * @return void
     */
    private function existeMovimientoCargado($fecha, $MotivoMovimiento, $descripcion, $valor){
        $arrMovimientos = $this->catalogoManager->getMovimientosPorFecha($fecha);

        foreach($arrMovimientos as $Movimiento){
            if ($this->esElMismoMovimiento($Movimiento, $MotivoMovimiento, $descripcion, $valor)){
                return true;
            }
        }

        return false;
    }

    //Se pueden mover al manager de catalogo lo que sigue de acá para abajo

    public function nuevoMotivoMovimiento($descripcion){
        $MotivoMovimiento = new MotivoMovimiento();
        $MotivoMovimiento->setDescripcion(trim($descripcion));

        $this->entityManager->persist($MotivoMovimiento);
        $this->entityManager->flush();

        return $MotivoMovimiento;
    }

    public function nuevoMovimiento($fecha, $MotivoMovimiento, $descripcion, $valor){
        $Movimiento = new Movimiento();
        $Movimiento->setFecha($fecha->format('Y-m-d\TH:i:s'));
        $Movimiento->setMotivoMovimiento($MotivoMovimiento);
        $Movimiento->setDescripcion(trim($descripcion));
        $Movimiento->setValor($valor);
        
        $this->entityManager->persist($Movimiento);
        $this->entityManager->flush();

        return $Movimiento;
    }

    public function actualizarCategoriaDeMotivoMovimiento($MotivoMovimiento, $IdCategoriaMov){
        $CategoriaMovimiento = $this->catalogoManager->getCategoriaMovimiento($IdCategoriaMov);

        $MotivoMovimiento->setCategoriaMovimiento($CategoriaMovimiento);

        $this->entityManager->persist($MotivoMovimiento);
        $this->entityManager->flush();
    }

    public function actualizarCategoria($CategoriaMovimiento, $dataPost){
        $TipoMovimiento = $this->catalogoManager->getTipoMovimiento($dataPost['selectIdTipoMov']);

        $CategoriaMovimiento->setDescripcion($dataPost['DescCategoria']);
        $CategoriaMovimiento->setTipoMovimiento($TipoMovimiento);

        $this->entityManager->persist($CategoriaMovimiento);
        $this->entityManager->flush();
    }

    public function actualizarTipoMovimiento($TipoMovimiento, $dataPost){
        $TipoMovimiento->setDescripcion($dataPost['DescTipoMovimiento']);
        $TipoMovimiento->setColor($dataPost['DescColor']);

        $this->entityManager->persist($TipoMovimiento);
        $this->entityManager->flush();
    }
}