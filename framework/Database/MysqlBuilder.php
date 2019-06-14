<?php


namespace Anton\Database;
//экранирование имен таблиц


class MysqlBuilder extends Builder
{
    /** @inheritDoc */
    protected function quote($value)
    {
        return "`{$value}`";
        // TODO: Implement quote() method.
    }
}