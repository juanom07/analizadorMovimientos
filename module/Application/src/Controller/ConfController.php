<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ConfController extends AbstractActionController
{
    // private $movimientosManager;
    private $catalogoManager;

    public function __construct($catalogoManager) {
        // $this->movimientosManager = $movimientosManager;
        $this->catalogoManager = $catalogoManager;
    }

    public function indexAction()
    {
        
        return new ViewModel();
    }

    public function motivosMovimientoAction(){
        
        $arrMotivoMovimientoJSON = $this->catalogoManager->getArrEntidadJSON('MotivoMovimiento');
        return new ViewModel([
            'arrMotivoMovimientoJSON' => $arrMotivoMovimientoJSON
        ]);
    }
}
