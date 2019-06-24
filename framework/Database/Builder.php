<?php


namespace Anton\Database;

use Anton\Exceptions\QueryBuilderException;
use Anton\Database\BuilderOperator as Op;

/**
 * Class Builder
 * @package Anton\Database
 */
abstract class Builder
{
    /**
     * @var Connect|null
     */
    protected $connect = null;

    protected $select = ['*'];
    protected $insert = null;
    protected $values = [];

    protected $from = '';
    protected $where = [];
    protected $orderby = '';
    protected $limit = null;
    protected $offset = null;
    /** @var Model $model */
    protected $model = null;

    /**
     * Builder constructor.
     * @param Model $model
     * Создаем объект класса Connect, если model не null
     * Записывыаем в свойство класса model, значение переданной переменной $model
     * переменная model должна быть экземпляром (наследника) класса Model
     */
    public function __construct($model = null)
    {
        $this->connect = new Connect();
        if (!is_null($model)) {
            $this->model = $model;
            $this->from = $model::getTable();
        }
    }

    /**
     * @param array $columns
     * @return Builder
     * Метод select по умолчанию принимает на вход массив ['*']
     * Если переданный аргумент является массивом, то возвращаем этот массив, иначе возвращаем массив содержащий аргументы функции ['*']
     */
    public function select($columns = ['*']) // ->select('id', 'name', 'email')
    {
        $this->select = ((is_array($columns)) ? $columns : func_get_args());            // func_get_args() - Возвращает массив, содержащий аргументы функции
        return $this;
    }

    /**
     * @param null $into
     * @return $this
     */
    public function insert($into = null)    //INSERT INTO
    {
        $this->insert = $into ?? $this->from;
        return $this;
    }

    /**
     * @param array $val
     * @return $this
     * метод
     */
    public function values($val = [])
    {
        $quotedValues = [];
        foreach ($val as $key => $value) {
            $quotedValues[] = [$this->quote($key), $this->convertValue($value)];
        }

        $this->values = $quotedValues;
        return $this;
    }


    /**
     * @param $tablename
     * @return $this
     * Метод from принимает на вход null
     * записываем в свойство from переданный аргумент метода from и возвраем $this
     * нужна ли проверка на тип данных? $this->convertValue($table) ?
     */
    public function from($tablename = null)
    {
        $this->from = $tablename;
        return $this;
    }

    /**
     * @param null $key
     * @param null $operator
     * @param null $val
     * @param string $boolean
     * @return $this
     * Метод where принимает на вход три аргумента (имя столбца, оператор, значение)
     * Определяем, были ли установлены переменные значением, отличным от NULL.
     * Записываем в свойство класса where(которое по умолчанию является пустым массивом) переданные аргументы
     * $key, $operator и с помощью метода convertValue устанавливаем значение аргумента $val
     * Возвращаем $this
     * @throws \Anton\Exceptions\UnaccaptableOperatorException
     */
    public function where($key = null, $operator = null, $val = null, $boolean = 'AND')
    {
        if (isset($key) && isset($operator) /*&& isset($val)*/) {                                                 //->where('name', '<>', 'Ivan ')->where('id', '>=', 1)
            $this->where[] = [
                $this->quote($key),
                Op::getOperator($operator, $val),
                $this->convertValue($val),
                (strtoupper($boolean) == 'OR' ? 'OR' : 'AND')
            ];
        }
        return $this;
    }

    /**
     * @param null $key
     * @param null $operator
     * @param null $val
     * @return Builder
     * @throws \Anton\Exceptions\UnaccaptableOperatorException
     */
    public function orWhere($key = null, $operator = null, $val = null)
    {
        return $this->where($key, $operator, $val, 'or');
    }

    /**
     * @param $key
     * @param $operator
     * @param $val
     * @return Builder
     * @throws \Anton\Exceptions\UnaccaptableOperatorException
     */
    public function andWhere($key, $operator, $val)
    {
        return $this->where($key, $operator, $val, 'and');
    }

    /**
     * @param null $order
     * @param string $direction
     * @return $this
     * Метод orderBy принимаем на вход два аргумента $order(имя столбца) по умолчанию null и $direction(порядок сортировки) по умолчанию 'ASC'(по возрастанию).
     * Зписываем в свойства класса orderby значение аргумента $order. Далее опеделяем значение второго аргумента и
     * выполняем конкатенацию с содержимым свойства $orderby.
     * Возвращаем $this
     */
    public function orderBy($order = null, $direction = 'ASC')
    {
        $this->orderby = $order;
        $this->orderby .= ($direction == 'ASC' ? ' ASC' : ' DESC');
        return $this;
    }

    /**
     * @param int|null $limit
     * @return $this
     * Метод limit принимает на вход два аргумента $limit(число строк которое нужно вывести) и $offset по умолчанию null, если второй аргумент указан, выводим интервал
     * Записываем в свойство класса limit переданный аргумент $limit.
     * Записываем в свойство класса offset переданный аргумент $offset.
     * возвращаем $this
     * сделать так что бы не передавать данные в массиве
     */
    public function limit($limit = null)
    {
        // limit 1 и limit 2, 6
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param null $offset
     * @return $this
     */
    public function offset($offset = null)
    {
        $this->offset = $offset;
        return $this;
    }


    /**
     * @return string
     * Метод toSql возвращает последовательно обработчики методов
     * отдельные обработчики для компиляции каждого метода
     * From может быть с AS
     * отдельно  дополнительные методы наприер $thi-buildSelect
     * @throws \Exception
     */
    public function toSql()
    {
        $sql = $this->generateSelectClause()
            . $this->generateFromClause()
            . $this->generateWhereClause()
            . $this->generateOrderByClause()
            . $this->generateLimitOffsetClause();
        return trim($sql);
    }


    /**
     * @return string
     * Метод generateSelectClause() возвращает конкатенацию строк SELECT, далее объединяем $this->select массив в строку.
     * Например, если в массиве одно значение получим "SELECT email", если в массиве два значения получи "SELECT email, id", если нет значений "SELECT *"
     */
    protected function generateSelectClause()
    {

        return 'SELECT ' . implode(', ', $this->select);

    }

    /**
     * @return string
     * Метод generateInsertClause - формирует SQL запрос
     * Создаем два пустых массива  $columns = массив для ключей $this->values(имена столбцов),  $values = массив для значений $this->values(значение)
     * Записываем в переменные $columns, $values данные переданные пользователем.
     * Даллее формурием sql запрос (регистрация нового пользователя, добавление в бд)
     * возвращаем результат конкатенации
     * Например: INSERT INTO users ( 'email', 'password', 'name' ) VALUES ('Ivan@mail.ru', 'Ivanov222', 'Ivan' )
     */
    protected function generateInsertClause()
    {

        $columns = [];
        $values = [];

        foreach ($this->values as $value) {
            $columns[] = $value[0];
            $values[] = $value[1];
        }

        $insertClause = ' ';
        if (!empty($this->insert)) {
            $insertClause .= 'INSERT INTO ' . $this->insert . ' (' . implode(', ',
                    $columns) . ') VALUES (' . implode(', ', $values) . ') ';
        }

        return $insertClause;

        // гененирует запрос Insert
        // возравщает нового пользователя
    }

    /**
     * @return array
     * @throws \Exception
     * Метод evalInsert - возвращает результат запроса к бд
     *
     */
    public function evalInsert()
    {
        $sqlInsert = $this->generateInsertClause();
        return $this->connect->execute($sqlInsert);
    }


    /**
     * @return string
     * Метод generateFromClause() обработывае метод from
     * Создаем переменную внутри метода $fromClause = ' ' которая содержит пробоел
     * Проверяем если содержимое свойство класса $this->from не пустое, тогда делааем конкатенацию строк "FROM '$this->from' ", иначе выводим ошибку
     * @throws \Exception
     */
    protected function generateFromClause()
    {
        $fromClause = ' ';
        if (!empty($this->from)) {
            $fromClause .= "FROM " . $this->quote($this->from);
        } else {
            throw new QueryBuilderException('No target table is presented');
        }
        return $fromClause;
    }

    /**
     * @return string
     * Метод generateWhereClause() обрабатывает метод Where.
     * Создаем переменную внутри метода $whereClause = ' ' которая содержит пробел
     * Проверяем если содержимое свойство класса $this->$where не пустое, тогда делаем конкатенацаю срок "WHERE "
     *
     */
    protected function generateWhereClause()
    {
        $whereClause = ' WHERE ';
        if (!empty($this->where)) {
            foreach ($this->where as $index => $where) {
                $boolean = array_pop($where);
                $whereClause .= (0 == $index) ? '' : (' ' . $boolean . ' ');
                $whereClause .= implode(' ', $where);
            }
        }
        return $whereClause;
    }

    /**
     * @return string
     * Метод
     * Обработчик для свойва orderby
     */
    protected function generateOrderByClause()
    {
        $orderByClause = ' ';
        if (!empty($this->orderby)) {
            $orderByClause .= "ORDER BY {$this->orderby}";
        }
        return $orderByClause;
    }

    /**
     * @return string
     * Обработчик для свойств limit и offset
     */
    protected function generateLimitOffsetClause()
    {
        $limitClause = ' ';
        if (!is_null($this->limit)) {
            $limit = (int)$this->limit;
            $limitClause .= "LIMIT {$limit}";
        }
        if (!is_null($this->offset)) {
            $offset = (int)$this->offset;
            $limitClause .= " OFFSET {$offset}";
        }
        return $limitClause;
    }


    /**
     * @return array Метод get отправляет  sql запрос
     * Метод get отправляет  sql запрос
     * @throws \Exception
     */
    public function get()
    {
        $sql = $this->toSql();
        $data = $this->connect->execute($sql);

        if (is_null($this->model)) {
            return $data;
        }

        return array_map(function ($item) {
            return new $this->model($item);
        }, $data);
    }

    public function first()
    {
        return $this->get()[0] ?? null;
    }

    /**
     * @param null $value
     * @return string|null
     * Проверка переменных на тип данных
     */
    protected function convertValue($value = null)
    {
        if (is_null($value)) {
            return 'NULL';
        }

        if (is_string($value)) {
            return "'$value'";
        }

        if (is_array($value)) {
            return "(" . implode(', ', $value) . ")";
        }

        if (is_int($value)) {
            return number_format($value, 0, '', '');
        }

        if (is_float($value)) {
            return number_format($value, 3, '.', '');
        }

        if (is_bool($value)) {
            return strtoupper(var_export($value, true));
        }

        return $value;
    }

    /**
     * @param string $value
     * @return string
     */
    abstract protected function quote($value);
}