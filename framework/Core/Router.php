<?php

namespace Anton\Core;

use Anton\Helpers\HttpHelper;

/**
 * Class Router
 * @package Anton\Core
 * Объявляем namespace Anton(framework)\Core
 * Используем класс Anton\Helpers\HttpHelper для дальнейшего использование класса HttpHelper
 */
class Router
{
    /**
     * @var array|mixed|null
     * протектед свойство $routes - содержит пустой массив. В дальнейшем в этом массиве будет хранится маршрутами
     * протектед свойство $handler(обработчик) - содержит пустой массив. В дальнейшем в этом массиве будет хранится  метод, контроллер и экшон
     * протектед свойство $namespace - содержит namespace Controllers в котором лежат контроллеры
     */
    protected $routes = [];
    protected $handler = [];
    protected $namespace = 'App\\Controllers\\';

    /**
     * Router constructor.
     * в конструкторе реализуем запись( в свойство класса routes) массива с маршрутами
     */
    function __construct()
    {
        /** @var  $arr = 'Application\config\routes.php' */
        $this->routes = config('routes', []);              //подключаем массив с маршрутами
    }

    /**
     * @return mixed
     * Метод run, проверяет , получает имя класса контроллера (например App\Controllers\NewsController),
     * проверяет есть ли такой класс, создает объект этого класса, и вызывает нужный метод.
     * Если $this->match вернул false, возвращаем код ошибки 404 "404 Маршрут не найдет"
     * Если $this->methodIs($this->handler['method']) вернул false, возвращаем код ошибки 405 '405 Неверный метод' (например вместо POST был передан GET),
     * Если ошибок не было, значит мы можем получить имя класса.
     * Записываем в переменную $controllerClass = $this->namespace . ucfirst($this->handler['controller']) . 'Controller'; Получится например $controllerClass = App\Controllers . News . Controller
     * Если такого класса не существует, возвращаем код ошибки 505 '505 Ничего не найдено',
     * Создаем объект полученного класса и
     * записываем в переенную $action вызываемый метод $action = $this->handler['action'] . 'Action'; Получится например $action = show . Action
     * Если такого метода нет, вернуть код ошибки 505 '505 Ничего не найдено',
     * Если все проверки были пройдены, возвращаем вызов метода  call_user_func([$controller, $action]); т.е. $controller->$action()
     */
    public function run()
    {
        if (!$this->match()) {
            return HttpHelper::sendCode(404);
        }

        if (!Request::methodIs($this->handler['method'])) {
            //var_dump(Request::method(), $this->handler);
            return HttpHelper::sendCode(405);
        }

        $controllerClass = $this->namespace . ucfirst($this->handler['controller']) . 'Controller';

        if (!class_exists($controllerClass)) {
            return HttpHelper::sendCode(505);
        }

        $controller = new $controllerClass;
        $action = $this->handler['action'] . 'Action';

        if (!method_exists($controller, $action)) {
            return HttpHelper::sendCode(505);
        }

        return call_user_func([$controller, $action]);
    }

    /**
     * @return bool
     * Метод match проверяет, есть ли указанный ключ в 'Application\config\routes.php'
     *
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
