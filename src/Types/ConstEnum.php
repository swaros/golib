<?php

namespace golib\Types;

/**
 * abstract class to use enum in a way to define all
 * possible bvalues as  a constant instead define
 * a seperate array. so just extend from this class
 * and add all constants
 */
abstract class ConstEnum extends Enum {

    private $constData = NULL;

    public function __construct($default = NULL, $errorMode = NULL) {
        parent::__construct($default, $errorMode);
    }

    public function getPossibleValueArray() {
        $myself = new \ReflectionClass($this);
        $this->constData = $myself->getConstants();
        return $this->constData;
    }

}

