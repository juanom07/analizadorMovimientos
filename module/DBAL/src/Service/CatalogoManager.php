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
            $TipoMovimiento = $this->entityManager->getRepository(TipoMovimiento::class)->findAll();
        }
        return $TipoMovimiento;
    }

    public function getCategoriaMovimiento($idCategoriaMovimiento = null){
        if ($idCategoriaMovimiento){
            $CategoriaMovimiento = $this->entityManager->getRepository(CategoriaMovimiento::class)->findOneBy(['id' => $idCategoriaMovimiento]);
        }else{
            $CategoriaMovimiento = $this->entityManager->getRepository(CategoriaMovimiento::class)->findAll();
        }
        return $CategoriaMovimiento;
    }

    public function getMotivoMovimiento($idMotivoMovimiento = null){
        if ($idMotivoMovimiento){
            $MotivoMovimiento = $this->entityManager->getRepository(MotivoMovimiento::class)->findOneBy(['id' => $idMotivoMovimiento]);
        }else{
            $MotivoMovimiento = $this->entityManager->getRepository(MotivoMovimiento::class)->findAll();
        }
        return $MotivoMovimiento;
    }

    public function getMovimiento($idMovimiento = null){
        if ($idMovimiento){
            $Movimiento = $this->entityManager->getRepository(Movimiento::class)->findOneBy(['id' => $idMovimiento]);
        }else{
            $Movimiento = $this->entityManager->getRepository(Movimiento::class)->findAll();
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

    public function getMotivoMovimientoPorDescripcion($descripcion){
        $MotivoMovimiento = $this->entityManager->getRepository(MotivoMovimiento::class)->findOneBy(['descripcion' => $descripcion]);

        return $MotivoMovimiento;
    }

    
    /**
     * LA CREACION Y MODIFICACION DE LAS ENTIDADES MOVERLAS LUEGO A UN MANAGER DIFERENTE
     */

}