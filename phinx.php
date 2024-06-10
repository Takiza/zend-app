<?php

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/data/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/data/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'u253920941_zend_app',
            'user' => 'u253920941_zend_app',
            'pass' => ':uIS0a#Xo9#A',
            'port' => 3306,
            'charset' => 'utf8',
        ],
    ],
];
