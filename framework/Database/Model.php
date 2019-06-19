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
use Anton\Exceptions\BuilderGetterException;

/**
 * Class Model
 * @property $table
 * @property $attributes
 *
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
     * @throws BuilderGetterException
     */
    public static function query()
    {
        /** @var Builder $builder */
        $builder = BuilderGetter::getBuilder();

        return new $builder(static::class);
    }

    public static function getTable()
    {
        return static::$tablename;
    }

    /**
     * @param $field
     * @param null $default
     * @return void $attributes[$field]
     * вернуть данные из модели из массива attributes
     * 2 параметра 1. field 2. default
     */
    public function get($field, $default = null)
    {
        return $this->attributes[$field] ??  $default;
    }
}