<?php
namespace golib\Types;

/**
 * Description of PropsFactory
 *
 * @author tziegler
 */
abstract class PropsFactory extends Props{

    private static $propData = array();
    private static $keyCache = array();

    private $__objectName = NULL;

    private $__keyName = NULL;

    private static $__ids = array();

    /**
     * constructs a set object
     * and keep all data til
     * the scope is running
     * @param string $primaryKeyName name of the primary key. this key must exists
     * @param array $data
     * @throws \InvalidArgumentException
     */
    public function __construct($primaryKeyName,array $data = NULL, $classname = NULL) {
        if ($primaryKeyName === NULL || !is_string($primaryKeyName)){
            throw new \InvalidArgumentException("Keyname is needed");
        }
        $this->__keyName = $primaryKeyName;
        if ($classname != NULL){
            $this->__objectName = $classname;
        }
        parent::__construct($data);
    }

    /**
     * returns the key for scope cache
     * @param string $key
     * @return string
     */
    private function getKey($key = NULL){
        if ($key === NULL) {
            $key = $this->__keyName;
            return $this->getClassKey() . '_' . $this->$key;
        }
        return $this->getClassKey() . '_' . $key;

    }

    /**
    * gets the current primary key
    * @return string the primary key
    */
    public function getPrimaryKey(){
        return $this->__keyName;
    }

    /**
     * ovewrite parent becasue of catching all
     * props
     * @param array $data
     */
    public function applyData($data = NULL) {
        parent::applyData($data);
        if ($data !=null){
            self::$propData[$this->getKey()] = $this;
            self::$keyCache[$this->getClassKey()] = $this->__keyName;
            $key = $this->__keyName;
            self::$__ids[$this->getClassKey()][$this->$key] = true;
        }
    }

    public function getIds(){
        return array_keys(self::$__ids[$this->getClassKey()]);
    }

    public function getPrimaryKey(){
        return $this->__keyName;
    }

    /**
     * set the classname
     * @param type $name
     */
    public function setClassName($name){
        $this->__objectName = $name;
    }

    /**
     * returns class specific key
     * @return string
     */
    private function getClassKey(){
        if ($this->__objectName != NULL){
            return $this->__objectName;
        }
        return get_class($this);
    }

    /**
     * returns the content by the
     * id if these already builded. if not is returns NULL
     * @param mixed $id
     * @return self
     */
    public function getProps($id){
        if (isset(self::$propData[$this->getKey($id)])){
            return self::$propData[$this->getKey($id)];
        }
        return NULL;
    }

    /**
     * fetch data if exists so the current object
     * is exchanges
     *
     * @param mixed $id
     * @return self
     */
    public function fetch($id){
        if (isset(self::$propData[$this->getKey($id)])){
            return self::$propData[$this->getKey($id)];
        }
        return false;
    }

    /**
     * copy propertie to self
     * @param self $source
     */
    private function copyProps(self $source){
        foreach ($source as $keyName => $data){
            if (substr($keyName, 0,2) !== '__'){
                $this->$keyName = $data;
            }
        }
    }


    /**
     *
     * @param type $id
     * @return self
     */
    public static function factory($id){
        $key = get_called_class() .'_'. $id;
        if (isset(self::$propData[$key])){
            return self::$propData[$key];
        }
        return NULL;
    }

    /**
     * checks if data already exists.
     * same as self::factory($id) === NULL
     * but just checking if entry created
     * @param type $id
     * @return type
     */
    public static function dataExists($id){
        $key = get_called_class() .'_'. $id;
        return isset(self::$propData[$key]);
    }
}
