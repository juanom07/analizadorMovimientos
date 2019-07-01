<?php
namespace Application\Service;

class MovimientosManager {
    
    private $entityManager;

    /**
     * Constructor del Servicio
     */
    public function __construct($entityManager) 
    {
        $this->entityManager = $entityManager;
    }

}