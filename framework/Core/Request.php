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
     * construct заполняет объекты
     * $uri
     * $path
     * $params
     * $method
     */
    protected final function __construct()
    {
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->method = $_SERVER['REQUEST_METHOD'] ?? self::METHOD_GET;
        $this->path = $_SERVER['PATH_INFO'] ?? '/';
        $this->params = array_merge($_GET, $_POST);
        parse_str($_SERVER['QUERY_STRING'], $this->query);
    }

    /**
     * getInstance - если нет static $Instance,  записывает в Instance новый экзепляр Request; возращает Instance
     * @return Request
     */
    public static function getInstance()                // паттерн single ton
    {
        if (empty(self::$instance)) {
            self::$instance = new static();             // аналог new Request. обращаемся к конструктору на прямую. паттер SingleTone
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
     * проверяет существование цепочки ключей params
     * @param string $keystring
     * @return bool
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
     * Нужно вернуть все поля(params) которые есть в запросу
     * @return array
     */
    public static function all()
    {
        return self::getInstance()->params;
    }

    /**
     * method - должен получить Instance. Вернуть его свойство method
     * @return mixed|string
     */
    public static function method()
    {
        return self::getInstance()->method;
    }

    /**
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
     * вернуть текущий uri
     * @return string
     */
    public static function uri()
    {
        return self::getInstance()->uri;
    }

    public static function path()
    {

        return self::getInstance()->path;
    }

    /**
     * @return array
     */
    public static function rules()
    {
        return [];
    }

    /**
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