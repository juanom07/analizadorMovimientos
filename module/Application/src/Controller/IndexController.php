<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    private $movimientosManager;
    private $catalogoManager;

    public function __construct($movimientosManager, $catalogoManager) {
        $this->movimientosManager = $movimientosManager;
        $this->catalogoManager = $catalogoManager;
    }

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $files = $this->params()->fromFiles();

            $this->movimientosManager->procesarArchivoExcel($files['archivo']['tmp_name']);
            $arrMovimientosJSON = $this->catalogoManager->getArrEntidadJSON('Movimiento');
            $view = new ViewModel([
                'mostrarJson' => $arrMovimientosJSON
            ]);
            $view->setTerminal(true);
            $view->setTemplate('application/index/mostrarJSON.phtml');
            return $view;
        }

        $arrMovimientosJSON = $this->catalogoManager->getArrEntidadJSON('Movimiento');
        return new ViewModel([
            'arrMovimientosJSON' => $arrMovimientosJSON
        ]);
    }
}
