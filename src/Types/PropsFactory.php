<?php

namespace golib\Types;

use InvalidArgumentException;

/**
 * Description of PropsFactory
 *
 * @author tziegler
 */
abstract class PropsFactory extends Props
{

    private static array $propData = array();
    private static array $keyCache = array();
    private string|null $__objectName = NULL;
    private string|null $__keyName = NULL;
    private static array $__ids = array();

    /**
     * constructs a set object
     * and keep all data til
     * the scope is running
     * @param string $primaryKeyName name of the primary key. this key must exists
     * @param array|null $data
     * @param string|null $classname
     */
    public function __construct(string $primaryKeyName, array $data = NULL,
                                string $classname = NULL)
    {
        if ($primaryKeyName === NULL || !is_string($primaryKeyName)) {
            throw new InvalidArgumentException("Key-Name is needed");
        }
        $this->__keyName = $primaryKeyName;
        if ($classname != NULL) {
            $this->__objectName = $classname;
        }
        parent::__construct($data);
    }

    /**
     * returns the key for scope cache
     * @param string|null $key
     * @return string
     */
    private function getKey(string $key = NULL): string
    {
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
    public function getPrimaryKey(): string
    {
        return $this->__keyName;
    }

    /**
     * overwrite parent because of catching all
     * props
     * @param array|object|null $data
     */
    public function applyData(array|object $data = NULL)
    {
        parent::applyData($data);
        if ($data != null) {
            self::$propData[$this->getKey()] = $this;
            self::$keyCache[$this->getClassKey()] = $this->__keyName;
            $key = $this->__keyName;
            self::$__ids[$this->getClassKey()][$this->$key] = true;
        }
    }

    public function getIds()
    {
        return array_keys(self::$__ids[$this->getClassKey()]);
    }

    /**
     * set the classname
     * @param string $name
     */
    public function setClassName(string $name)
    {
        $this->__objectName = $name;
    }

    /**
     * returns class specific key
     * @return false|string
     */
    private function getClassKey(): false|string
    {
        if ($this->__objectName != NULL) {
            return $this->__objectName;
        }
        return get_class($this);
    }

    /**
     * returns the content by the
     * id if these already build. if not is returns NULL
     * @param string $id
     * @return PropsFactory|null
     */
    public function getProps(string $id): self|null
    {
        if (isset(self::$propData[$this->getKey($id)])) {
            return self::$propData[$this->getKey($id)];
        }
        return NULL;
    }

    /**
     * fetch data if exists so the current object
     * is exchanges
     *
     * @param mixed $id
     * @return bool|mixed
     */
    public function fetch($id)
    {
        if (isset(self::$propData[$this->getKey($id)])) {
            return self::$propData[$this->getKey($id)];
        }
        return false;
    }

    /**
     * copy property to self
     * @param self $source
     */
    private function copyProps(self $source)
    {
        foreach ($source as $keyName => $data) {
            if (substr($keyName, 0, 2) !== '__') {
                $this->$keyName = $data;
            }
        }
    }

    /**
     *
     * @param string $id
     * @return self
     */
    public static function factory(string $id)
    {
        $key = get_called_class() . '_' . $id;
        if (isset(self::$propData[$key])) {
            return self::$propData[$key];
        }
        return NULL;
    }

    /**
     * checks if data already exists.
     * same as self::factory($id) === NULL
     * but just checking if entry created
     * @param string $id
     * @return bool
     */
    public static function dataExists(string $id): bool
    {
        $key = get_called_class() . '_' . $id;
        return isset(self::$propData[$key]);
    }

}
