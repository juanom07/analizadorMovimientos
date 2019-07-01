<?php
namespace Application\Service\Factory;
use Interop\Container\ContainerInterface; 
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\MovimientosManager;

class MovimientosManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        // $catalogoManager = $container->get(CatalogoManager::class); 
        
        return new MovimientosManager($entityManager);
    }
}