<?php

namespace Application;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\TableGateway;
use Application\Model\TaskTable;
use Application\Model\ProductTable;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'task' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/task[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\TaskController::class,
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],
            'product' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/product[/:action[/:id]]',
                    'defaults' => [
                        'controller' => Controller\ProductController::class,
                        'action'     => 'index',
                    ],
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                ],
            ],
            'sql-examples' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/sql-examples',
                    'defaults' => [
                        'controller' => Controller\SqlExamplesController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\TaskController::class => Controller\Factory\TaskControllerFactory::class,
            Controller\ProductController::class => Controller\Factory\ProductControllerFactory::class,
            Controller\SqlExamplesController::class => InvokableFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            TaskTable::class => function($container) {
                $tableGateway = $container->get('TaskTableGateway');
                return new TaskTable($tableGateway);
            },
            ProductTable::class => function($container) {
                $tableGateway = $container->get('ProductTableGateway');
                return new ProductTable($tableGateway);
            },
            'TaskTableGateway' => function ($container) {
                $dbAdapter = $container->get(AdapterInterface::class);
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Model\Task());
                return new TableGateway('tasks', $dbAdapter, null, $resultSetPrototype);
            },
            'ProductTableGateway' => function ($container) {
                $dbAdapter = $container->get(AdapterInterface::class);
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new Model\Product());
                return new TableGateway('products', $dbAdapter, null, $resultSetPrototype);
            },
            AdapterInterface::class => function ($container) {
                $config = $container->get('config');
                return new Adapter($config['db']);
            },
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
            'application/task/index'  => __DIR__ . '/../view/application/task/index.phtml',
            'application/product/index'  => __DIR__ . '/../view/application/product/index.phtml',
            'application/sql-examples/index' => __DIR__ . '/../view/application/sql-examples/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'pagination' => [
        'items_per_page' => 10,
    ],
];
