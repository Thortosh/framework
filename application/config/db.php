<?php

return [
    'connect' => [
        'driver' => 'mysql', // array ( 0 => 'mysql', 1 => 'pgsql', 2 => 'sqlite', ); throw new Exception()
        'host' => 'localhost',
        'database' => 'framework',
        'port' => '3306',
        'user' => 'root',
        'password' => '',
    ],
    'builders' => [
        'mysql' => 'MysqlBuilder',
        'pgsql' => 'PgsqlBuilder',
        'sqlite' => 'SqliteBuilders',
    ],
];