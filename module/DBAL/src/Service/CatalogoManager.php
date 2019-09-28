<?php
namespace DBAL\Service;

use DBAL\Entity\TipoMovimiento;
use DBAL\Entity\CategoriaMovimiento;
use DBAL\Entity\MotivoMovimiento;
use DBAL\Entity\Movimiento;

class CatalogoManager {
    
    private $entityManager;

    /**
     * Constructor del Servicio
     */
    public function __construct($entityManager) 
    {
        $this->entityManager = $entityManager;
    }

    public function getTipoMovimiento($idTipoMovimiento = null){
        if ($idTipoMovimiento){
            $TipoMovimiento = $this->entityManager->getRepository(TipoMovimiento::class)->findOneBy(['id' => $idTipoMovimiento]);
        }else{
            $TipoMovimiento = $this->entityManager->getRepository(TipoMovimiento::class)->findBy([], ['descripcion' => 'ASC']);
        }
        return $TipoMovimiento;
    }

    public function getCategoriaMovimiento($idCategoriaMovimiento = null){
        if ($idCategoriaMovimiento){
            $CategoriaMovimiento = $this->entityManager->getRepository(CategoriaMovimiento::class)->findOneBy(['id' => $idCategoriaMovimiento]);
        }else{
            $CategoriaMovimiento = $this->entityManager->getRepository(CategoriaMovimiento::class)->findBy([], ['descripcion' => 'ASC']);
        }
        return $CategoriaMovimiento;
    }

    public function getMotivoMovimiento($idMotivoMovimiento = null){
        if ($idMotivoMovimiento){
            $MotivoMovimiento = $this->entityManager->getRepository(MotivoMovimiento::class)->findOneBy(['id' => $idMotivoMovimiento]);
        }else{
            $MotivoMovimiento = $this->entityManager->getRepository(MotivoMovimiento::class)->findBy([], ['descripcion' => 'ASC']);
        }
        return $MotivoMovimiento;
    }

    public function getMovimiento($idMovimiento = null){
        if ($idMovimiento){
            $Movimiento = $this->entityManager->getRepository(Movimiento::class)->findOneBy(['id' => $idMovimiento]);
        }else{
            $Movimiento = $this->entityManager->getRepository(Movimiento::class)->findBy([], ['fecha' => 'DESC']);
        }
        
        return $Movimiento;
    }

    /**
     * Funcion que transforma un arreglo de entidades, en un JSON.
     *
     * @param [array] $arrEntidad
     * @return String
     */
    public function arrEntidadesAJSON($arrEntidad){
        $output = [];
        
        foreach($arrEntidad as $Entidad){
            $output[] = $Entidad->getJSON();
        }
        $output = implode(", ", $output);
        return '[' . $output . ']';
    }

    /**
     * Funcion que devuelve el arreglo de todas las entidades
     * en formato JSON para ser enviado a la vista.
     * 
     * Recibe por parametro el nombre de la entidad, el cual debe ser
     * exactamente igual al de la clase PHP.
     *
     * @return string
     */
    public function getArrEntidadJSON($nombreEntidad){
        $nombreFuncion = 'get'.$nombreEntidad;
        $arrEntidad = $this->$nombreFuncion();
        return $this->arrEntidadesAJSON($arrEntidad);
    }

    /**
     * Funcion para analisar si un motivo de movimiento es por gasto o por ingreso,
     * dependiendo del valor del movimiento.
     * 
     * La unica posiblidad es q existan dos motivos iguales, y uno corresponde a ingresos y el otro a gastos,
     * por eso se evalua el valor del movimiento.
     *
     * @param [array] $arrMotivoMovimiento
     * @param [float] $valor
     * @return MotivoMovimiento
     */
    private function procesarAnalisisMotivoMovimiento($arrMotivoMovimiento, $valor){
        foreach ($arrMotivoMovimiento as $MotivoMovimiento){
            if ($MotivoMovimiento->getCategoriaMovimiento()->getTipoMovimiento()->esIngreso()
                && floatval($valor) > 0)//No importa que el parseo del float no sea perfecto, 
                                        //es solo para determinar si es positivo o negativo
            {
                return $MotivoMovimiento;
            }

            if ($MotivoMovimiento->getCategoriaMovimiento()->getTipoMovimiento()->esGasto()
                && floatval($valor) < 0) 
            {
                return $MotivoMovimiento;
            }
        }

        return null;
    }

    public function getMotivoMovimientoPorDescripcion($descripcion, $valor = null){
        $arrMotivoMovimiento = $this->entityManager->getRepository(MotivoMovimiento::class)->findBy(['descripcion' => $descripcion]);

        if (count($arrMotivoMovimiento) > 1 && isset($valor)){
            //analizar cual corresponde.. seguro viene uno por ingreso y otro por egreso
            return $this->procesarAnalisisMotivoMovimiento($arrMotivoMovimiento, $valor);
        }else if (count($arrMotivoMovimiento) > 0){
            return $arrMotivoMovimiento[0]; //Es unico
        }

        return null;
    }

    /**
     * Funcion que retorna un Movimiento para ser mostrado como ejemplo.
     * Se utiliza en la vinculación del motivo del movimiento con su categoria.
     * Le permite al administrador ver el detalle del movimiento y poder tomar una desición.
     *
     * @param [MotivoMovimiento] $MotivoMovimiento
     * @return Movimiento | null
     */
    public function getMovimientoDeEjemplo($MotivoMovimiento){
        $Movimiento = $this->entityManager->getRepository(Movimiento::class)->findOneBy(['MotivoMovimiento' => $MotivoMovimiento]);

        return $Movimiento;
    }

    /**
     * Esta funcion busca el/los movimientos que existan cargados en la base con una determinada fecha.
     * Se utiliza para evitar la duplicación de movimientos a la hora de levantar el archivo de importación.
     *
     * @param [datetime] $fecha
     * @return array
     */
    public function getMovimientosPorFecha($fecha){
        $Movimientos = $this->entityManager->getRepository(Movimiento::class)->findBy(['fecha' => $fecha->format('Y-m-d\TH:i:s')]);

        return $Movimientos;
    }

    public function getMovimientosRealizadosConDebito(){
        $MotivoMovimiento = $this->getMotivoMovimientoPorDescripcion('COMPRA TARJETA DE DEBITO');
        $Movimientos = $this->entityManager->getRepository(Movimiento::class)->findBy(['MotivoMovimiento' => $MotivoMovimiento], ['fecha' => 'DESC']);

        return $Movimientos;
    }
    
    /**
     * LA CREACION Y MODIFICACION DE LAS ENTIDADES MOVERLAS LUEGO A UN MANAGER DIFERENTE
     */

}