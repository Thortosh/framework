<?php


namespace Anton\Core;

class Request
{
    /**
     * Все возможные методы запроса
     * для проверки соответсвия метода (напр. GET):
     * Request::methodIs(Request::METHOD_GET)
     */
    const METHOD_OPTIONS = 'OPTIONS';
    const METHOD_GET = 'GET';
    const METHOD_HEAD = 'HEAD';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_TRACE = 'TRACE';
    const METHOD_CONNECT = 'CONNECT';

    /**
     * Свойства экземпляра Request
     */
    protected $uri = '';
    protected $path = '';
    protected $method = '';
    protected $params = [];
    protected $query = [];
    /**
     * Экземпляр Request
     * @var Request $instance
     */
    protected static $instance = null;

    /**
     * Request constructor.
     * construct заполняет свойства объекта
     * $uri - URI, который был предоставлен для доступа к этой странице
     * $path - Содержит любой предоставленный пользователем путь, содержащийся после имени скрипта, но до строки запроса, если она есть.
     * Например, если текущий скрипт запрошен по URL http://www.example.com/php/path_info.php/some/stuff?foo=bar, то переменная $_SERVER['PATH_INFO'] будет содержать /some/stuff. Либо '/'
     * $params содержит массив $_GET и $_POST (array_merge — Сливает один или большее количество массивов)
     * $method - содержит 'REQUEST_METHOD' .Какой метод был использован для запроса страницы; к примеру 'GET', 'HEAD', 'POST', 'PUT'. Ссылка на констану self::METHOD_GET
     * -parse_str — Разбирает строку в переменные
     * 'QUERY_STRING' - Строка запроса, если есть, через которую была открыта страница.
     */
    protected final function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'] ?? self::METHOD_GET;
        $this->path = $_SERVER['PATH_INFO'] ?? '/';
        $this->params = array_merge($_GET, $_POST);
        /* $this->query = */parse_str($_SERVER['QUERY_STRING'], $this->query);
    }

    /**
     * getInstance - если  static $instance = null,  записывает в $instance новый экзепляр Request (аналог new Request. обращаемся к конструктору на прямую. паттер SingleTone); возращает Instance
     * @return Request
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * возвращает значение из params по цепочке ключей
     * @param $keystring
     * @param null $default
     * @return array|string|null
     */
    public static function get($keystring, $default = null)
    {
        if (!self::has($keystring)) {
            return $default;
        }

        $keys = explode('.', $keystring);
        $buffer = self::all();

        foreach ($keys as $key) {
            $buffer = $buffer[$key];
        }
        return $buffer;
    }

    /**
     * Статический метод has - принимает на вход один аргумент($keystring) проверяет существование цепочки ключей params
     * @param string $keystring
     * @return bool
     * Записываем в переменную $keys разбитую строку ($keystring) с помощью разделителя ('.')
     * Записываем в переменную $buffer = self::all(); (Метод all - возвращает все поля(params) которые есть в запросе)
     * Возвращаем true
     *
     */
    public static function has($keystring)
    {
        $keys = explode('.', $keystring);
        $buffer = self::all();

        foreach ($keys as $key) {
            if (!array_key_exists($key, $buffer)) {
                return false;
            }
            $buffer = $buffer[$key];
        }
        return true;
    }

    /**
     * Метод all - возвращает все поля(params) которые есть в запросе
     * SingleTone getInstance
     * @return array
     */
    public static function all()
    {
        return self::getInstance()->params;
    }

    /**
     * Метод method - должен получить Instance. Вернуть его свойство method
     * @return mixed|string
     */
    public static function method()
    {
        return self::getInstance()->method;
    }

    /**
     * Метод query принимает на вход два аргумента $key (по дефолту null) и $default (по дефолту null)
     * Записываем в переменную $query
     * @param null $key
     * @param null $default
     * @return array
     */
    public static function query($key = null, $default = null)
    {
        $query = self::getInstance()->query;
        if (is_null($key)) {
            return $query;
        }
        return $query[$key] ?? $default;
    }

    /**
     * methodIs - сравнить переданный метод и тот метод который внутри Request
     * если методы совпадают вернуть true иначе false
     * @param $method
     * @return bool
     */
    public static function methodIs($method)
    {
        return $method == self::method();
    }

    /**
     * Метод uri возвращает текущий uri
     * @return string
     */
    public static function uri()
    {
        return self::getInstance()->uri;
    }

    /**
     * Метод path - возвращает путь
     * @return mixed|string
     */
    public static function path()
    {

        return self::getInstance()->path;
    }

    /**
     *
     * @return array
     */
    public static function rules()
    {
        return [];
    }

    /**
     * Метод validate - проверяет все ли поля заполнены
     * ипользуется в AuthHelper.php
     * @param array $required
     * @return array
     */
    public static function validate(array $required = [])
    {
        $errors = [];
        foreach ($required as $field) {
            if (empty(self::get($field))) {
                $errors[$field] = 'Это полне нужно заполнить';
            }
        }
        return $errors;
    }
}