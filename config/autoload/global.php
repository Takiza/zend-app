<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

return [
    'db' => [
        'driver' => $_ENV['DB_DRIVER'],
        'database' => $_ENV['DB_DATABASE'],
        'username' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
        'hostname' => $_ENV['DB_HOSTNAME'],
    ],
    'service_manager' => [
        'factories' => [
            Laminas\Db\Adapter\AdapterInterface::class => Laminas\Db\Adapter\AdapterServiceFactory::class,
        ],
    ],
];
