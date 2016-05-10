<?php
namespace golib\Types;

/**
 * Description of Props
 *
 * @author tziegler
 *
 * this class is used for assign values
 * from a associated Array to
 * class Properties
 *
 */
abstract class Props {

    private static $replaceChars = array(
        '.','#','_','-'
    );

    /**
     *
     * @param array $data
     */
    public function __construct($data = NULL) {
        $this->applyData($data);
    }

    /**
     * apply given data to properties
     * @param array $data
     */

    public function applyData($data = NULL){
        if (NULL != $data && is_array($data)) {
            foreach ($data as $propName => $propValue) {
                $this->assignValue($propName, $propValue);
            }
        }

    }

    /**
     * assign value to a existing propertie and
     * cast depending on defined default value
     * @param string $propName
     * @param boolean $propValue
     */
    public function assignExisting($propName, $propValue) {
        if (is_bool($this->$propName)) {
            if (strtolower($propValue) == 'false') {
                $propValue = false;
            }
            $this->$propName = (bool) $propValue;
        } elseif (is_int($this->$propName)) {
            $this->$propName = (int) $propValue;
        } elseif (is_bool($this->$propName)) {
            $this->$propName = (bool) $propValue;
        } elseif ($this->$propName instanceof Timer || $this->$propName == MapConst::TIMER) {
            $this->$propName = new Timer($propValue);
        } else {
            $this->$propName = $propValue;
        }
    }

    /**
     * main assign value method.
     *
     * @param string $propNameOrig
     * @param mixed $propValue
     */
    public function assignValue($propNameOrig, $propValue) {
        $propNameA = str_replace(self::$replaceChars, '_', $propNameOrig);
        $propName = preg_replace("/[^A-Za-z0-9_]/", "", $propNameA);
        if (property_exists($this, $propName) && $this->$propName !== NULL) {
            $this->assignExisting($propName, $propValue);

        } elseif ($propName !=='') {
            $this->$propName = $propValue;
        }
    }

}
