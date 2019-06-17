<?php


namespace Anton\Database;

use Anton\Exceptions\UnaccaptableOperatorException;

class BuilderOperator
{
    const OP_EQ = '=';                      // равно (strig, numeric)
    const OP_NEQ = '<>';                    // не равно (strig, numeric)
    const OP_LT = '<';                      // меньше ( numeric)
    const OP_LTE = '<=';                    // меньше равно ( numeric )
    const OP_GT = '>';                      // больше ( numeric)
    const OP_GTE = '>=';                    // больше равно ( numeric)
    const OP_LIKE = 'LIKE';                 // как (string)
    const OP_IS = 'IS';                     // это (NULL, boolean)
    const OP_NOT = 'NOT';                   // нет (NULL, boolean)
    const OP_IN = 'IN';                     // имеется в (array)
    const OP_NIN = 'NOT IN';                // нет в (NULL, boolean)

    const AVAILABLE_OPERATORS = [
        '=',                                // равно
        '==',                               // строгое равно
        '===',                              // эквивалентно
        '!=',                               // не равно
        '!==',                              // строгое не равно
        '<>',                               // не равно
        'is',                               // это
        'not',                              // не
        'in',                               // в
        'not in',                           // нет в
        '<',                                // меньше
        '<=',                               // меньше либо равно
        '>',                                // больше
        '>=',                               // больше либо равно
        'like'                              // как
    ];

    const DEFAULT_OPERATOR = self::OP_EQ;
    /**
     * OPERATORS_COMP_MAP содержит массив с опреаторами сравнения
     */
    const OPERATORS_COMP_MAP = [
        '<' => self::OP_LT,
        '<=' => self::OP_LTE,
        '>' => self::OP_GT,
        '>=' => self::OP_GTE,
    ];
    /**
     * OPERATORS_EQ_MAP содержит массив с операторами "равно"
     */
    const OPERATORS_EQ_MAP = [
        '=' => self::OP_EQ,
        '==' => self::OP_EQ,
        '===' => self::OP_EQ,
        'is' => self::OP_EQ,
    ];
    /**
     * OPERATOR_NEQ_MAP содержит массив с операторами "не равно"
     */
    const OPERATORS_NEQ_MAP = [
        '!=' => self::OP_NEQ,
        '!==' => self::OP_NEQ,
        '<>' => self::OP_NEQ,
        'not' => self::OP_NEQ,
    ];

    const ARRAY_OPERATORS = [
        'in' => self::OP_IN,
        'not in' => self::OP_NIN,
    ];

    /**
     * @param $operator
     * @param $value
     *
     * @return string
     * Метод getOperator принимает на вход два аргумента $operator(оператор) и $value(значение)
     * Присваиваем переменной $op значение переданного аргумента $operator и преобразуем строку в нижний регистр
     * Если $op нет в массиве AVAILABLE_OPERATORS возвращаем константу OP_EQ которая равна "="
     * Иначе, проверяем тип данных переданных в $value и проверяем переданный оператор
     * @throws UnaccaptableOperatorException
     */
    public static function getOperator($operator, $value)
    {
        $op = strtolower($operator);

        if (!in_array($op, self::AVAILABLE_OPERATORS)) {
            throw new UnaccaptableOperatorException($op, 'any');
        }

        switch (gettype($value)) {
            case ('array'):
                return self::getArrayOperator($op);
            case ('integer'):
            case ('double'):
            case ('float'):
                return self::getNumericOperator($op);
            case ('string'):
                return self::getStringOperator($op);
            case ('boolean'):
                return self::getBoolOperator($op);
            case ('NULL'):
                return self::getNullOperator($op);
        }

        return self::DEFAULT_OPERATOR;
    }

    /**
     * @param $op
     * @return string
     * @throws UnaccaptableOperatorException
     */
    protected static function getArrayOperator($op)
    {
        if (!key_exists($op, self::ARRAY_OPERATORS)) {
            throw new UnaccaptableOperatorException($op, 'array');
        }

        return self::ARRAY_OPERATORS[$op];
    }

    /**
     * @param $op
     * @return string
     * @throws UnaccaptableOperatorException
     */
    protected static function getNullOperator($op)
    {
        if (key_exists($op, self::OPERATORS_EQ_MAP)) {
            return self::OP_IS;
        }
        if (key_exists($op, self::OPERATORS_NEQ_MAP)) {
            return self::OP_NOT;
        }

        throw new UnaccaptableOperatorException($op, 'null');
    }

    /**
     * @param $op
     * @return mixed|string
     * @throws UnaccaptableOperatorException
     */
    protected static function getStringOperator($op)
    {
        if ($op == self::OP_LIKE) {
            return self::OP_LIKE;
        }

        if ($operator = self::getComparingOperator($op)) {
            return $operator;
        }

        throw new UnaccaptableOperatorException($op, 'string');
    }

    /**
     * @param $op
     * @return mixed
     * @throws UnaccaptableOperatorException
     */
    protected static function getNumericOperator($op)
    {
        if ($operator = self::getComparingOperator($op)) {
            return $operator;
        }

        throw new UnaccaptableOperatorException($op, 'number');
    }

    /**
     * @param $op
     * @return string
     * @throws UnaccaptableOperatorException
     */
    protected static function getBoolOperator($op)
    {
        if (key_exists($op, self::OPERATORS_EQ_MAP)) {
            return self::OP_EQ;
        }
        if (key_exists($op, self::OPERATORS_NEQ_MAP)) {
            return self::OP_NEQ;
        }

        throw new UnaccaptableOperatorException($op, 'null');
    }

    protected static function getComparingOperator($op)
    {
        if (key_exists($op, self::OPERATORS_EQ_MAP)) {
            return self::OPERATORS_EQ_MAP[$op];
        }

        if (key_exists($op, self::OPERATORS_NEQ_MAP)) {
            return self::OPERATORS_NEQ_MAP[$op];
        }

        if (key_exists($op, self::OPERATORS_COMP_MAP)) {
            return self::OPERATORS_COMP_MAP[$op];
        }

        return null;
    }
}