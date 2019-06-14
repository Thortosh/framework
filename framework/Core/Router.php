<?php

namespace Anton\Core;

use Anton\Helpers\HttpHelper;

class Router
{
    protected $routes = [];
    protected $handler = [];
    protected $namespace = 'App\\Controllers\\';

    function __construct()
    {
        /** @var  $arr = 'Application\config\routes.php' */
        $this->routes = config('routes');              //подключаем массив с маршрутами
    }

    /**
     * @return mixed
     */
    public function run()         //функция для запуска роутера
    {
        if (!$this->match()) {                                  // Если match вернул false вернуть ошибку
            return HttpHelper::sendCode(404);
        }
        if (!Request::methodIs($this->handler['method'])) {
            //var_dump(Request::method(), $this->handler);
            return HttpHelper::sendCode(405);
        }

        $controllerClass = $this->namespace . ucfirst($this->handler['controller']) . 'Controller';        // Получаем имя класса контроллера например App\Controllers\NewsController

        if (!class_exists($controllerClass)) {                                      // Если такого класса не существует вернуть ошибку
            return HttpHelper::sendCode(505);
        }

        $controller = new $controllerClass;                                         // Создаем объект
        $action = $this->handler['action'] . 'Action';

        if (!method_exists($controller, $action)) {
            return HttpHelper::sendCode(505);
        }

        return call_user_func([$controller, $action]); //  $controller->$action()
    }

    /**
     * @return bool
     * uri нет в массиве роутов -> вернуть false
     * записать в handler контроллер и экшен из соответсвующего роута и вернуть true
     */
    public function match()
    {
        if (!array_key_exists(Request::path(), $this->routes)) {
            return false;
        }

        $this->handler = $this->routes[Request::path()];
        return true;
    }
}
