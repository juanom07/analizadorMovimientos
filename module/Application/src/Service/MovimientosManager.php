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
        // var_dump($sheetData);

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
     * Como esta compuesta la fila de datos?
     * 'A' => Fecha del movimiento
     * 'B' => Sucursal del banco
     * 'C' => Detalle del movimiento (dividir en dos partes)
     * 'D' => Un codigo
     * 'E' => Monto del movimiento
     *
     * @param [type] $filaDatos
     * @return void
     */
    private function procesarFilasArchivo($filaDatos){
        $fechaOriginal = $filaDatos['A'];
        $detalle = $filaDatos['C'];
        $valor = $filaDatos['E'];

        $nuevoString = '';
        //Procesamiento del detalle del movimiento
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

        if (count($arrStrings) > 1){
            $motivoMovimiento = $this->catalogoManager->getMotivoMovimientoPorDescripcion($arrStrings[0]);

            if (!isset($motivoMovimiento)){
                $motivoMovimiento = new MotivoMovimiento();
                $motivoMovimiento->setDescripcion(trim($arrStrings[0]));

                $this->entityManager->persist($motivoMovimiento);
                $this->entityManager->flush();
            }

            $formato = 'd/m/Y H:i';
            $fecha = \DateTime::createFromFormat($formato, $fechaOriginal);
            
            $Movimiento = new Movimiento();
            $Movimiento->setFecha($fecha->format('Y-m-d\TH:i:s'));
            $Movimiento->setMotivoMovimiento($motivoMovimiento);
            $Movimiento->setValor($valor);
            $Movimiento->setDescripcion(trim($arrStrings[1]));

            $this->entityManager->persist($Movimiento);
            $this->entityManager->flush();
        }else{
            //Fallo al reconocer el movimiento
            return null;
        }
        
        

    }

}