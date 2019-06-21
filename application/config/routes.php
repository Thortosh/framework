<?php
// маршруты
use Anton\Core\Request;

return [
    '/' => [                                // Главная страница
        'controller' => 'main',             // контроллер - main, action - index
        'action' => 'index',
        'method' => Request::METHOD_GET
    ],

    '/account/login' => [                   // Вход в аккаунт
        'controller' => 'account',
        'action' => 'login',
        'method' => Request::METHOD_GET
    ],

    '/account/logoff' => [                  // Выход из аккаунта
        'controller' => 'account',
        'action' => 'logoff',
        'method' => Request::METHOD_GET
    ],

    '/account/auth' => [                    //  Авториза́ция
        'controller' => 'account',
        'action' => 'auth',
        'method' => Request::METHOD_POST
    ],

    '/account/me' => [                      // Страница пользоваеля
        'controller' => 'account',
        'action' => 'me',
        'method' => Request::METHOD_GET
    ],
    '/account/register' => [            // Страница регистрации
        'controller' => 'account',
        'action' => 'register',
        'method' => Request::METHOD_GET
    ],
    '/account/create' => [            // URL создание пользователя
        'controller' => 'account',
        'action' => 'create',
        'method' => Request::METHOD_POST
    ],

    '/news/show' => [                       // Новости (содержимое приложения)
        'controller' => 'news',
        'action' => 'show',
        'method' => Request::METHOD_GET
    ],
];