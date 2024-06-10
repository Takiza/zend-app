<?php

namespace Api;

use Laminas\ServiceManager\Factory\InvokableFactory;
use Application\Model\ProductTable;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\View\Strategy\JsonStrategy;

return [
    'service_manager' => [
        'factories' => [
            ProductTable::class => function($container) {
                $tableGateway = $container->get('ProductTableGateway');
                return new ProductTable($tableGateway);
            },
            'ProductTableGateway' => function ($container) {
                $dbAdapter = $container->get(AdapterInterface::class);
                $resultSetPrototype = new ResultSet();
                $resultSetPrototype->setArrayObjectPrototype(new \Application\Model\Product());
                return new TableGateway('products', $dbAdapter, null, $resultSetPrototype);
            },
            AdapterInterface::class => function ($container) {
                $config = $container->get('config');
                return new Adapter($config['db']);
            },
            Middleware\ApiKeyMiddleware::class => function ($container) {
                $apiKey = getenv('API_KEY');
                return new Middleware\ApiKeyMiddleware($apiKey);
            },
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ProductController::class => \Api\Controller\Factory\ProductControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'api-products' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/api/v1/products[/:id]',
                    'defaults' => [
                        'controller' => Controller\ProductController::class,
                    ],
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
