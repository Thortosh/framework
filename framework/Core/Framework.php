<?php

namespace Anton\Core;

class Framework
{


    public static function run()
    {
        self::init();
        self::dispatch();
    }

    private static function init()
    {

        // Определяем константы
        define("DS", DIRECTORY_SEPARATOR);                      // DIRECTORY_SEPARATOR - разделитель пути "\"
        define("ROOT", getcwd() . DS);                          // getcwd — Получает имя текущего рабочего каталога
        define("APP_PATH", ROOT . 'application' . DS);          // смотрит в папку Application
        define("FRAMEWORK_PATH", ROOT . "Framework" . DS);      // смотрит в папку Framework
        define("PUBLIC_PATH", ROOT . "public" . DS);            // смотрит в папку Public
        define("CONFIG_PATH", APP_PATH . "config" . DS);        // смотрит в папку Config
        define("VIEW_PATH", APP_PATH . "views" . DS);           // смотрит в папку View

        define("CORE_PATH", FRAMEWORK_PATH . "Core" . DS);      // смотрит в папку Core
        define('DB_PATH', FRAMEWORK_PATH . "Database" . DS);    // смотрит в папку Database
        define("LIB_PATH", FRAMEWORK_PATH . "Libraries" . DS);  // смотрит в папку Libraries
        define("HELPER_PATH", FRAMEWORK_PATH . "Helpers" . DS); // смотрит в папку Helpers
        define("UPLOAD_PATH", PUBLIC_PATH . "Uploads" . DS);    // смотрит в папку Uploads

        $GLOBALS['config'] = require CONFIG_PATH . "config.php";    // записываем в переменную config подключения config.php

        session_start();
    }

    private static function dispatch()
    {
        $router = new Router();
        $router->run();
    }
}