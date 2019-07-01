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

    public function __construct($movimientosManager) {
        $this->movimientosManager = $movimientosManager;
    }

    public function indexAction()
    {
        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $files = $this->params()->fromFiles();

            $this->movimientosManager->procesarArchivoExcel($files['archivo']['tmp_name']);
        }
        return new ViewModel();
    }
}
