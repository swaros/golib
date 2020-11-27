<?php

namespace golib\Types;

use InvalidArgumentException;

/**
 * Description of valueSet
 *
 * @author tziegler
 *
 * this is a Element that handles
 * separated entries like 158,225,369
 * and make sure all needed elements are set count wise.
 * except you return NULL on getMaxEntries.this will
 * disable the count check
 *
 */
abstract class ValueSet
{

    private array|string|null $values = NULL;

    /**
     * ValueSet constructor.
     * @param array|string|null $initialEntry
     */
    public function __construct(string|array $initialEntry = NULL)
    {
        if ($initialEntry !== NULL) {
            $this->applyData($initialEntry, true);
        }

        $this->checkCount();
    }

    /**
     * apply content via string or array
     * @param array|string|null $applyEntry
     * @param boolean $full if true the whole set will be overwritten.on flase just the fisrt entries
     */
    public function applyData(array|string $applyEntry = NULL, $full = true)
    {
        if (is_array($applyEntry)) {
            if ($full) {
                $this->values = $applyEntry;
            } else {
                $this->values = array_replace($this->values, $applyEntry);
            }
        } elseif (is_string($applyEntry)) {
            if ($full) {
                $this->values = explode($this->getDelimiter(), $applyEntry);
            } else {
                $this->values = array_replace($this->values, explode($this->getDelimiter(), $applyEntry));
            }
        } else {
            throw new InvalidArgumentException("Argument must be an array or Separated string");
        }

    }

    /**
     * checks if the expected count is given
     * triggers an error if getMaxEntries Returns a number
     * and this number is not equal to the count of elements.
     */
    private function checkCount()
    {
        $cnt = (int)$this->getMaxEntries();
        if ($cnt !== NULL && count($this->values) !== $cnt) {
            trigger_error("Wrong count of Elements. Expected are $cnt. But right now there are " . count($this->values));
        }

    }

    /**
     * magig getter for string operations
     * @return string
     */
    function __toString()
    {
        $this->checkCount();
        return $this->formatToString();
    }

    /**
     * mapper for imploding content
     * @return string
     */
    public function selfImplode(): string
    {
        return implode($this->getDelimiter(), $this->values);
    }

    /**
     * return the max allowed count of entries.
     * return NULL if no limit needed
     * @return int|null
     */
    abstract protected function getMaxEntries(): int|null;

    /**
     * return the delimiter
     * @return string
     */
    abstract protected function getDelimiter(): string;

    /**
     * Return the string format ot these entries.
     * @return String formatted string
     */
    abstract protected function formatToString(): string;

}
