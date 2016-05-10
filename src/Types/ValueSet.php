<?php
namespace golib\Types;

/**
 * Description of valueSet
 *
 * @author tziegler
 *
 * this is a Element that handles
 * sperated entries like 158,225,369
 * and make sure all needed elements are set count wise.
 * except you return NULL on getMaxEntries.this will
 * disable the count check
 *
 */
abstract class ValueSet {

    private $values = NULL;

    /**
     *
     * @param string/array $initialEntrie a array or seperatded string
     */
    public function __construct($initialEntrie = NULL) {
        if ($initialEntrie !== NULL){
            $this->applyData($initialEntrie, true);
        }

        $this->checkCount();
    }

    /**
     * apply content via string or array
     * @param array/string $applyEntrie is an array or and seperated string
     * @param boolean $full if true the whole set will be overwritten.on flase just the fisrt entries
     * @throws \InvalidArgumentException
     */
    public function applyData($applyEntrie = NULL, $full = true){

        if (is_array($applyEntrie)){
            if ($full) {
                $this->values = $applyEntrie;
            } else {
                $this->values = array_replace($this->values, $applyEntrie);
            }
        }elseif (is_string($applyEntrie)){
            if ($full) {
                $this->values = explode($this->getDelimiter(), $applyEntrie);
            } else {
                $this->values = array_replace($this->values,explode($this->getDelimiter(), $applyEntrie));
            }
        } else {
            throw new \InvalidArgumentException("Argument must be an array or Speparated string");
        }

    }

    /**
     * checks if the expected count is given
     * triggers an error if getMaxEntries Returns a number
     * and this number is not equal to the count of elements.
     */
    private function checkCount(){
        $cnt = (int) $this->getMaxEntries();
        if ($cnt !== NULL && count($this->values) !== $cnt){
            trigger_error("Wrong count of Elements. Expected are $cnt. But right now there are ". count($this->values));
        }

    }

    /**
     * magig getter for string operations
     * @return string
     */
    function __toString() {
        $this->checkCount();
        return $this->formatToString();
    }

    /**
     * mapper for imploding content
     * @return type
     */
    public function selfImplode(){
        return implode($this->getDelimiter(), $this->values);
    }

    /**
     * return the max allowed count of entries.
     * return NULL if no limit needed
     * @return int
     */
    abstract protected function getMaxEntries();

    /**
     * return the delimiter
     * @return string
     */
    abstract protected function getDelimiter();

    /**
     * Return the string format ot these entries.
     * @return String formated string
     */
    abstract protected function formatToString();

}
