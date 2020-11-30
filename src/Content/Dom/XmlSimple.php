<?php

namespace golib\Content\Dom;

use SimpleXMLElement;

/**
 * Description of XmlSimple
 *
 * @author tziegler
 */
abstract class XmlSimple
{
    /**
     * parsed content from xml
     * @var SimpleXMLElement
     */
    private SimpleXMLElement $content;


    public function __construct($xmlContent)
    {
        $this->getData($xmlContent);
    }

    public function xPath($xPath)
    {
        return $this->content->xpath($xPath);
    }


    /**
     * get the content or false of node.
     * in case of false there is no node exists
     * @param string type $xPath
     * @return string/boolean
     */
    public function getNodeContent($xPath)
    {
        $arr = $this->content->xpath($xPath);
        return (string)trim(end($arr));
    }


    /**
     * catch 2 given attributes form content. (content defined by xpath)
     * and returns the values as array key value pair.
     * so for example:
     * you have a xml like:
     *  <data>
     *    <entrypoint id="1337" url="github.com"/>
     *    <entrypoint id="999" url="google.com"/>
     *  </data>
     *
     * you can now build a keyed array by calling:
     *      getNodeKeyedArray("//data", "url", "id")
     * this will return a array like:
     *   Array
     *   (
     *      [github.com] => 1337
     *      [google.com] => 999
     *   )
     *
     * @param string $xPath
     * @param string $keyName
     * @param string $valueName
     * @param bool $flat
     * @return array
     */
    public function getNodeKeyedArray(string $xPath, string $keyName, string $valueName, bool $flat = true): array
    {
        $usePath = "{$xPath}/*[@*]";
        $res = $this->content->xpath($usePath);
        $return = array();

        foreach ($res as $set) {
            $key = (string)$set[$keyName];
            $value = (string)$set[$valueName];
            if ($flat === true) {
                $return[$key] = $value;
            } else {
                $return[] = array($key => $value);
            }
        }

        return $return;

    }

    /**
     * returns xpath from array as array
     * @param string $xPath
     * @return array
     */
    public function getNodeAttrAsArray(string $xPath): array
    {
        $usePath = "{$xPath}[@*]";
        $res = $this->content->xpath($usePath);
        foreach ($res as $set) {
            $dArr = (array)$set->attributes();
            return $dArr['@attributes'];
        }
        return [];
    }


    /**
     * reads an specific attribute from node
     * @param string $xPath
     * @param string $atrName
     * @return string|null
     */
    public function getNodeAttribute(string $xPath, string $atrName): string|null
    {
        $res = $this->content->xpath($xPath);
        foreach ($res as $set) {
            return (string)$set[$atrName];

        }
        return NULL;
    }

    /**
     *
     * @param SimpleXMLElement $element
     * @param string $atrName
     * @return string
     */
    public function getNodeAttrBySmplXml(SimpleXMLElement $element, string $atrName): string
    {
        $set = $element->attributes();
        return (string)$set[$atrName];
    }

    public function getNodeChildBySmplXml(SimpleXMLElement $element, string $atrName): SimpleXMLElement|null
    {
        $set = $element->children();
        if (!isset($set->$atrName)) {
            return null;
        }
        return $set->$atrName;
    }

    /**
     * returns all attributes as key-value array
     * @param SimpleXMLElement $element
     * @return array
     */
    public function getNodeAttrArrayBySmplXml(SimpleXMLElement $element): array
    {
        if ($element->attributes()->count() > 0) {
            $data = (array)$element->attributes();
            return (array)$data['@attributes'];
        }
        return [];
    }


    /**
     * return node attribute as integer.
     * the value that stands for fail an be set as
     * optional parameter (-1 by default)
     * @param string $xPath
     * @param string $atrName
     * @param int $failReturn
     * @return int
     */
    public function getNodeAttributeInt(string $xPath,string $atrName,int $failReturn = -1)
    {
        $val = $this->getNodeAttribute($xPath, $atrName);
        if ($val === NULL || $val === false || !is_numeric($val)) {
            return $failReturn;
        }
        return (int)$val;
    }

    /**
     * return node attribute value from xpath as boolean.
     * by default, if no entry exists or the value
     * can not be interpreted as bool, it returns false.
     * this default can be set to true with the 3. parameter
     * @param string $xPath
     * @param string $atrName
     * @param bool $failReturn
     * @return boolean
     */
    public function getNodeAttributeBool(string $xPath, string $atrName, bool $failReturn = false): bool
    {
        $val = $this->getNodeAttribute($xPath, $atrName);
        if ($val === NULL) {
            return $failReturn;
        }
        // string sets true/false
        if (strtolower($val) === 'false') {
            return false;
        }
        if (strtolower($val) === 'true') {
            return true;
        }
        return (bool)$val;
    }


    /**
     * parse the xml
     * @param string $data xml source
     */
    private function getData(string $data)
    {
        $this->content = simplexml_load_string($data);
    }

}
