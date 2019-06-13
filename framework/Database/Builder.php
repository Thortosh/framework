<?php


namespace Anton\Database;

use Anton\Exceptions\QueryBuilderException;

class Builder
{
    protected $connect = null;
    protected $select = ['*'];
    protected $from = '';
    protected $where = [];
    protected $orderby = '';
    protected $limit = null;
    protected $offset = null;

    /**
     * Builder constructor.
     */
    public function __construct()
    {
        $this->connect = new Connect();
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
     * @return $this
     * Метод where принимает на вход три аргумента (имя(столбца), оператор, значение)
     * Определяем, были ли установлены переменные значением, отличным от NULL.
     * Записываем в свойство класса where(которое по умолчанию является пустым массивом) переданные аргументы
     * $key, $operator и с помощью метода convertValue устанавливаем значение аргумента $val
     * Возвращаем $this
     */
    public function where($key = null, $operator = null, $val = null)
    {
        //реализовать where так что бы можно было вызвать несколько раз для нескольких условий
        if (isset($key) && isset($operator) /*&& isset($val)*/) {                   //->where('name', '<>', 'Ivan ')->where('id', '>=', 1)
            $this->where[] = [$key, $operator, $this->convertValue($val)];
        }
        return $this;
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
        return $this->generateSelectClause()
            . $this->generateFromClause()
            . $this->generateWhereClause()
            . $this->generateOrderByClause()
            . $this->generateLimitOffsetClause();
    }

    /**
     * @return string
     * Метод generateSelectClause() возвращает конкатенацию строк SELECT далее объединяем $this->select массив в строку.
     * Например, если в массиве одно значение получим "SELECT email", если в массиве два значения получи "SELECT email, id", если нет значений "SELECT *"
     */
    protected function generateSelectClause()
    {

        return 'SELECT ' . implode(', ', $this->select);

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
            $fromClause .= "FROM {$this->from}";
        } else {
            throw new QueryBuilderException('No target table is presented');
        }
        return $fromClause;
    }

    /**
     * @return string
     * Метод generateWhereClause() обрабатывает метод Where.
     * Создаем переменную внутри метода $whereClause = ' ' которая содержит пробоел
     * Проверяем если содержимое свойство класса $this->$where не пустое, тогда делаем конкатенацаю срок "WHERE "
     *
     */
    protected function generateWhereClause()
    {
        $whereClause = ' ';
        if (!empty($this->where)) {
            $whereClause .= 'WHERE ' . implode(
                    ' AND ',
                    array_map(function ($where) {
                        return implode(' ', $where);
                    }, $this->where)
                );
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
        return $this->connect->execute($sql);
        // компилит запрос и возвращает пользователю данные из бд
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

}