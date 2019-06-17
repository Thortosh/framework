<?php


namespace Anton\Exceptions;


class UnaccaptableOperatorException extends \Exception
{
    public function __construct($operator, $type)
    {
        parent::__construct("Unaccaptable operator '{$operator}' for type '{$type}'");
    }
}