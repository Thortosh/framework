<?php

namespace Anton\Database;

// framework/core/Model.class.php

// Base Model Class
// TODO: конкретный билдер.
// TODO: опредяем тип билдера по типу драйвера
// TODO: у модели должен быть метод query (static), который будет возвращать экземпляр builder и передавать в него $tablename
// TODO: уже в момент получения builder, у него уже должен быть прописан FROM, который мы берем из экземпляра модели
// TODO: почистить конструктор

use \Anton\Database\Builder;

/**
 * Class Model
 * @property $table
 * @property $attributes
 */
class Model
{
    protected static $tablename;
    protected $attributes = [];

    /**
     * Model constructor
     * заполняет массив $attributes переданными данными
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @return Builder
     */
    public static function query()
    {
        //что обратится в статическом методе к свойству экземпляра класса используется (new static)
        return new Builder(static::class);
    }

    public static function getTable()
    {
        return static::$tablename;
    }

    /**
     * @return void $attributes[$field]
     */
    public function get()
    {
    }
}