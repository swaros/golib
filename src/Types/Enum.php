<?php
namespace golib\Types;

/**
 * Description of Enum
 *
 * @author tziegler
 *
 * enumerator class
 *
 *
 */
abstract class Enum {


    /**
     * current Value
     * @var string
     */
    private $value = NULL;

    private $errorMode = EnumDef::ERROR_MODE_EXCEPTION;

    public function __construct($default = NULL,$errorMode = NULL) {
        $this->value = $default;
        if ($errorMode !==NULL && is_int($errorMode)){
            $this->errorMode = $errorMode;
        }
        $this->checkValue($default);
    }

    /**
     * checks if given $value
     * valid
     * @param type $value
     * @throws \InvalidArgumentException
     */
    private function checkValue($value){


        $check = $this->getPossibleValueArray();
        if (!is_array($check) || empty($check)){
            $this->handleError("No Check Array defined (getPossibleValueArray())",  EnumException::NoEnumsDefined);
        }
        if (!in_array($value, $check)){
            $this->handleError("Invalid Value", EnumException::Invalidvalue);
        }
    }

    private function handleError($msg, $code){
        if ($this->errorMode === EnumDef::ERROR_MODE_TRIGGER_ERROR){
            trigger_error($msg.' CODE:'.$code);
        } else {
            throw new EnumException($msg, $code);
        }
    }


    function __toString() {
        return $this->value;
    }

    public function getValue(){
        return $this->value;
    }

    /**
     * must return an array of valid entries
     * @retun array
     */
    abstract function getPossibleValueArray();

}
