<?php

namespace golib\Types;

use InvalidArgumentException;

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
abstract class EnumSet
{
    const MODE_INT = 2;

    const ERROR_MODE_EXCEPTION = 1;
    const ERROR_MODE_TRIGGER_ERROR = 2;

    /**
     * current Value
     * @var array
     */
    private array $values;


    /**
     * EnumSet constructor.
     * @param array $default
     * @throws EnumException
     */
    public function __construct(array $default = array())
    {
        $this->values = $default;
        $this->checkValue($default);
    }

    /**
     * checks if given $value
     * valid
     * @param array $valueSet
     * @throws InvalidArgumentException
     * @throws EnumException
     */
    private function checkValue(array $valueSet)
    {
        $check = $this->getPossibleValueArray();
        if (!is_array($check) || empty($check)) {
            throw new EnumException("No Check Array defined (getPossibleValuArray())", EnumException::NoEnumsDefined);
        }

        foreach ($valueSet as $value) {
            if (!in_array($value, $check)) {
                throw new EnumException("Invalid Value, {$value} is not in ENUM", EnumException::InvalidValue);
            }
        }
    }


    function __toString()
    {
        return implode(',', $this->values);
    }

    /**
     * @return array
     */
    public function getValue(): array
    {
        return array_unique($this->values);
    }

    /**
     * must return an array of valid entries
     * @retun array
     */
    abstract function getPossibleValueArray();

}
