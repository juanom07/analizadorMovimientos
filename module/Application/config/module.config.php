<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'conf' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/conf',
                    'defaults' => [
                        'controller' => Controller\ConfController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'motivosMovimiento' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/motivosMovimiento[/:action[/:id]]',
                            'defaults' => [
                                'controller' => Controller\ConfController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'constraints' => [
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id' => '[a-zA-Z0-9_-]*',
                        ],
                        'may_terminate' => true,
                    ],
                    'categoriaMovimiento' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/categoriaMovimiento[/:action[/:id]]',
                            'defaults' => [
                                'controller' => Controller\ConfController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'constraints' => [
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id' => '[a-zA-Z0-9_-]*',
                        ],
                        'may_terminate' => true,
                    ],
                    'tiposMovimiento' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/tiposMovimiento[/:action[/:id]]',
                            'defaults' => [
                                'controller' => Controller\ConfController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'constraints' => [
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id' => '[a-zA-Z0-9_-]*',
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
            'reportes' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/reportes[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\ReportesController::class,
                        'action'     => 'index',
                    ],
                ],
                'constraints' => [
                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    'id' => '[a-zA-Z0-9_-]*',
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\ConfController::class => Controller\Factory\ConfControllerFactory::class,
            Controller\ReportesController::class => Controller\Factory\ReportesControllerFactory::class
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\MovimientosManager::class => Service\Factory\MovimientosManagerFactory::class
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
