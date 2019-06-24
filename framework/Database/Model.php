<?php

namespace Anton\Database;

// framework/core/Model.class.php

// Base Model Class
// TODO: конкретный билдер.
// TODO: опредяем тип билдера по типу драйвера
// TODO: у модели должен быть метод query (static), который будет возвращать экземпляр builder и передавать в него $tablename
// TODO: уже в момент получения builder, у него уже должен быть прописан FROM, который мы берем из экземпляра модели
// TODO: почистить конструктор

use Anton\Exceptions\BuilderGetterException;

/**
 * Class Model
 * @property $table
 * @property $attributes
 *
 */
class Model implements \Serializable
{
    protected static $tablename;
    protected $attributes = [];
    /**
     * @var array $hidden
     * Список полей которые нельзя показывать при сериализации
     */
    protected $hidden = [];

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

    /**
     * @return mixed
     */
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
        return $this->attributes[$field] ?? $default;
    }

    /**
     * @param array $values
     * @return array
     * @throws BuilderGetterException
     * @throws \Anton\Exceptions\UnaccaptableOperatorException
     * В метод create - получить пользователя по email, обернуть в модель
     */
    public function create($values = [])
    {
        $builder = self::query();
        $builder->insert()->values($values)->evalInsert();

        return $builder->where('email', '=', $values['email'] ?? '')->first();
    }


    /**
     * @return mixed
     */
    public function all()
    {
        return $this->attributes;
    }

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        $user = $this->all();
        foreach ($this->hidden as $field) {
            unset($user[$field]);
        }
        return serialize($user);
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $this->attributes = unserialize($serialized);
    }
}