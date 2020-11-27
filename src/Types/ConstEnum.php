<?php

namespace golib\Types;

use ReflectionClass;
use ReflectionException;

/**
 * abstract class to use enum in a way to define all
 * possible values as a constant instead define
 * a separate array. so just extend from this class
 * and add all constants
 */
abstract class ConstEnum extends Enum
{

    public function __construct($default = NULL, $errorMode = NULL)
    {
        parent::__construct($default, $errorMode);
    }

    public function getPossibleValueArray(): array
    {
        try {
            $myself = new ReflectionClass($this);
            return $myself->getConstants();
        } catch (ReflectionException $e) {
            return [];
        }
    }

}

