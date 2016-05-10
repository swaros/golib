<?php
namespace golib\Types;

/**
 * Description of Enum Set
 *
 * @author tziegler
 * @author mstuebs
 *
 * enumerator set class
 *
 *
 */
abstract class EnumSet {

    const MODE_STRING = 1;
    const MODE_INT = 2;

    const ERROR_MODE_EXCEPTION = 1;
    const ERROR_MODE_TRIGGER_ERROR = 2;

    /**
     * current Value
     * @var array
     */
    private $values = array();

    private $errorMode = self::ERROR_MODE_EXCEPTION;

    public function __construct($default = array(),$errorMode = NULL) {
        $this->values = $default;
        if ($errorMode !==NULL && is_int($errorMode)){
            $this->errorMode = $errorMode;
        }
        $this->checkValue($default);
    }

    /**
     * checks if given $value
     * valid
     * @param array $valueSet
     * @throws \InvalidArgumentException
     */
    private function checkValue($valueSet){
        $check = $this->getPossibleValueArray();
        if (!is_array($check) || empty($check)){
            $this->handleError("No Check Array defined (getPossibleValuArray())",  EnumException::NoEnumsDefined);
        }

        if (!is_array($valueSet)){
            $this->handleError("Invalid Value, must be Array", EnumException::Invalidvalue);
        }

        foreach ($valueSet as $value) {
            if (!in_array($value, $check)){
                $this->handleError("Invalid Value, {$value} is not in ENUM", EnumException::Invalidvalue);
            }
        }
    }

    private function handleError($msg, $code){
        if ($this->errorMode === self::ERROR_MODE_TRIGGER_ERROR){
            trigger_error($msg.' CODE:'.$code);
        } else {
            throw new EnumException($msg, $code);
        }
    }


    function __toString() {
        return implode(',', $this->values);
    }

    /**
     * @return Array
     */
    public function getValue(){
        return array_unique($this->values);
    }

    /**
     * must return an array of valid entries
     * @retun array
     */
    abstract function getPossibleValueArray();

}
