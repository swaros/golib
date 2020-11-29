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
     * @return array|null
     */
    public function getNodeKeyedArray(string $xPath, string $keyName, string $valueName, bool $flat = true)
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
    public function getNodeAttribute(string $xPath, string $atrName):string|null
    {
        $res = $this->content->xpath($xPath);
        foreach ($res as $set) {
            return (string)$set[$atrName];

        }
        return NULL;
    }

    /**
     *
     * @param \SimpleXMLElement $element
     * @param type $atrName
     * @return type
     */
    public function getNodeAttrBySmplXml(\SimpleXMLElement $element, $atrName)
    {
        $set = $element->attributes();
        return (string)$set[$atrName];
    }

    /**
     *
     * @param \SimpleXMLElement $element
     * @param type $atrName
     * @return type
     */
    public function getNodeChildBySmplXml(\SimpleXMLElement $element, $atrName)
    {
        $set = $element->children();
        if (!isset($set->$atrName)) {
            return null;
        }
        return $set->$atrName;
    }

    /**
     *
     * @param \SimpleXMLElement $element
     * @param type $atrName
     * @return type
     */
    public function getNodeAttrArrayBySmplXml(\SimpleXMLElement $element)
    {
        $data = (array)$element->attributes();
        return (array)$data['@attributes'];
    }


    /**
     * return node attribut in any case as integer.
     * the value that stands for fail an be set as
     * optional paramater (-1 by default)
     * @param string $xPath
     * @param string $atrName
     * @param bool $failReturn
     * @return int
     */
    public function getNodeAttributeInt($xPath, $atrName, $failReturn = -1)
    {
        $val = $this->getNodeAttribute($xPath, $atrName);
        if ($val === NULL || $val === false || !is_numeric($val)) {
            return $failReturn;
        }
        return (int)$val;
    }

    /**
     * return node attribut value in boolean
     * @param type $xPath
     * @param type $atrName
     * @param type $failReturn
     * @return boolean
     */
    public function getNodeAttributeBool($xPath, $atrName, $failReturn = false)
    {
        $val = $this->getNodeAttribute($xPath, $atrName);
        // string sets true/false
        if (strtolower($val) === 'false') {
            return false;
        }
        if (strtolower($val) === 'true') {
            return true;
        }
        if ($val === NULL || !is_bool($val)) {

            return $failReturn;
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
