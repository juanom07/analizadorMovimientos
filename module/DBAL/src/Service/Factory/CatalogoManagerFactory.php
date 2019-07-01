<?php
namespace DBAL\Service\Factory;
use Interop\Container\ContainerInterface; 
use Zend\ServiceManager\Factory\FactoryInterface;
use DBAL\Service\CatalogoManager;

class CatalogoManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        
        return new CatalogoManager($entityManager);
    }
}