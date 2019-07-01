<?php
namespace Application\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;

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
        
        }
    }

}