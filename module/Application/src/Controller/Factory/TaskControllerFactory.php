<?php

namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Application\Controller\TaskController;
use Application\Model\TaskTable;

class TaskControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $table = $container->get(TaskTable::class);
        $config = $container->get('config');

        return new TaskController($table, $config);
    }
}
