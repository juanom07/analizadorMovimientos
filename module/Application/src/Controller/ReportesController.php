<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ReportesController extends AbstractActionController
{
    private $movimientosManager;
    private $catalogoManager;

    public function __construct($movimientosManager, $catalogoManager) {
        $this->movimientosManager = $movimientosManager;
        $this->catalogoManager = $catalogoManager;
    }

    public function indexAction()
    {
        return new ViewModel();
    }

    public function generalAction()
    {
        $arrMovimientosJSON = $this->catalogoManager->getArrEntidadJSON('Movimiento');
        
        return new ViewModel([
            'arrMovimientosJSON' => $arrMovimientosJSON
        ]);
    }

    public function comprasDebitoAction()
    {
        $arrMovimientosDebito = $this->catalogoManager->getMovimientosRealizadosConDebito();
        $arrMovimientosJSON = $this->catalogoManager->arrEntidadesAJSON($arrMovimientosDebito);
        
        return new ViewModel([
            'arrMovimientosJSON' => $arrMovimientosJSON
        ]);
    }

    public function categoriaMovimientoAction()
    {
        $arrMovimientosJSON = $this->catalogoManager->getArrEntidadJSON('Movimiento');
        
        return new ViewModel([
            'arrMovimientosJSON' => $arrMovimientosJSON
        ]);
    }
}
