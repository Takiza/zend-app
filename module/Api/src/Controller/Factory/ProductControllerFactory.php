<?php

namespace Api\Controller\Factory;

use Api\Controller\ProductController;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Model\ProductTable;

class ProductControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $productTable = $container->get(ProductTable::class);
        return new ProductController($productTable);
    }
}
