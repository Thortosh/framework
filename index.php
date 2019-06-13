<?php

////Автозагрузка класса spl_autoload_register
//spl_autoload_register(function ($class) {
//    $path = str_replace('\\', '/', $class . '.php');        // заменяет обратные слэш
//    if (file_exists($path)) {                                                  // подключаем, если файл с такимименем существует
//        require $path;
//    }
//});

require __DIR__ . '/vendor/autoload.php';
Anton\Core\Framework::run();

