<?php


namespace Anton\Database;


use Anton\Exceptions\BuilderGetterException;

/**
 * Class BuilderGetter
 * @package Anton\Database
 *
 */
class BuilderGetter
{
    /**
     * @return string
     * @throws BuilderGetterException
     */
    public static function getBuilder()
    {
        if (is_null(config('db.connect.driver'))) {
            throw new BuilderGetterException ('Driver not defined');
        }
        $driverDb = config('db.connect.driver');

        if (is_null(config("db.builders.{$driverDb}"))) {
            throw new BuilderGetterException ('Builder not defined');
        }
        $builder = BUILDERS_NAMESPACE . config("db.builders.{$driverDb}");

        if (!class_exists($builder)) {
            throw new BuilderGetterException ("Builder Class '{$builder}' not exist");
        }

        return $builder;
    }
}