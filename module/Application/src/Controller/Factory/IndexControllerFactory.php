<?php
namespace Application\Controller\Factory;
use Interop\Container\ContainerInterface; 
use Zend\ServiceManager\Factory\FactoryInterface;

use Application\Controller\IndexController;
use Application\Service\MovimientosManager;
use DBAL\Service\CatalogoManager;
/**
 * This is the factory for AuthController. Its purpose is to instantiate the controller
 * and inject dependencies into its constructor.
 */
class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $movimientosManager = $container->get(MovimientosManager::class);
        $catalogoManager = $container->get(CatalogoManager::class); 

        return new IndexController($movimientosManager, $catalogoManager);
    }
}