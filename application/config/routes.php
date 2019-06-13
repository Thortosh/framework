<?php
// маршруты
use Anton\Core\Request;

return [
    '/' => [
        'controller' => 'main',             // контроллер - main, action - index
        'action' => 'index',
        'method' => Request::METHOD_GET
    ],

    '/account/login' => [
        'controller' => 'account',
        'action' => 'login',
        'method' => Request::METHOD_GET
    ],

    '/account/logoff' => [
        'controller' => 'account',
        'action' => 'logoff',
        'method' => Request::METHOD_GET
    ],

    '/account/auth' => [
        'controller' => 'account',
        'action' => 'auth',
        'method' => Request::METHOD_POST
    ],

    '/account/me' => [
        'controller' => 'account',
        'action' => 'me',
        'method' => Request::METHOD_GET
    ],

    '/news/show' => [
        'controller' => 'news',
        'action' => 'show',
        'method' => Request::METHOD_GET
    ],
];