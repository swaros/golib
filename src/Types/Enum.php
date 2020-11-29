<?php

namespace golib\Types;

use InvalidArgumentException;

/**
 * Description of Enum
 *
 * @author tziegler
 *
 * enumerator class
 *
 *
 */
abstract class Enum
{


    /**
     * current Value
     * @var string|null
     */
    private null|string $value;

    /**
     * Enum constructor.
     * @param null $default
     * @throws EnumException
     */
    public function __construct($default = NULL)
    {
        $this->value = $default;
        $this->checkValue($default);
    }

    /**
     * checks if given $value
     * valid
     * @param mixed $value
     * @throws InvalidArgumentException|EnumException
     */
    private function checkValue($value)
    {

        $check = $this->getPossibleValueArray();
        if (!is_array($check) || empty($check)) {
            throw new EnumException("No Check Array defined (getPossibleValueArray())", EnumException::NoEnumsDefined);
        }
        if (!in_array($value, $check)) {
            throw new EnumException("Invalid Value", EnumException::InvalidValue);

        }
    }

    function __toString()
    {
        return $this->value;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * must return an array of valid entries
     * @retun array
     */
    abstract function getPossibleValueArray();

}
