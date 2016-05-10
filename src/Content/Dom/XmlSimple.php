<?php
namespace golib\Content\Dom;
/**
 * Description of XmlSimple
 *
 * @author tziegler
 */
abstract class XmlSimple {
    /**
     * parsed content from xml
     * @var SimpleXMLElement
     */
    private $content;


    public function __construct($xmlContent) {
        $this->getData($xmlContent);
    }

    public function xPath($xPath){
        return $this->content->xpath($xPath);
    }


    /**
     * get the content or false of node.
     * in case of fasle there is no node exists
     * @param type $xPath
     * @return string/boolean
     */
    public function getNodeContent($xPath){
        if ($this->content->xpath($xPath) !== null){
            return (string)trim(end($this->content->xpath($xPath)));
        }
        return false;
    }


    /**
     * catch 2 given attributes and returns the values as array key value
     * @param string $xPath
     * @param string $keyName
     * @param string $valueName
     * @param type $flat
     * @return type
     */
    public function getNodeKeyedArray($xPath,$keyName,$valueName, $flat = true){
        $usePath = "{$xPath}/*[@*]";
        $res = $this->content->xpath($usePath);
        $return = array();
        if ( $res !== null){
            foreach ($res as $set){
               $key = (string)$set[$keyName];
               $value = (string)$set[$valueName];
               if ($flat === true){
                   $return[$key] = $value;
               } else {
                $return[] = array($key => $value);
               }
            }

            return $return;
        }
        return NULL;
    }

    /**
     * catch 2 given attributes and returns the values as array key value
     * @param string $xPath
     * @param string $keyName
     * @param string $valueName
     * @param type $flat
     * @return type
     */
    public function getNodeAttrAsArray($xPath){
        $usePath = "{$xPath}[@*]";
        $res = $this->content->xpath($usePath);
        if ( $res !== null){
            foreach ($res as $set){
                $dArr = (array) $set->attributes();
                return $dArr['@attributes'];
            }

        }
        return NULL;
    }


    /**
     * reads an spcific attribute from node
     * @param type $xPath
     * @param type $atrName
     * @return string
     */
    public function getNodeAttribute($xPath,$atrName){
        $res = $this->content->xpath($xPath);
        if ($res !== null){
            foreach ($res as $set){
                return (string)$set[$atrName];

            }
        }

        return NULL;
    }

    /**
     *
     * @param \Sflib\Config\SimpleXMLElement $element
     * @param type $atrName
     * @return type
     */
    public function getNodeAttrBySmplXml(\SimpleXMLElement $element,$atrName){
        $set = $element->attributes();
        return (string)$set[$atrName];
    }

    /**
     *
     * @param \Sflib\Config\SimpleXMLElement $element
     * @param type $atrName
     * @return type
     */
    public function getNodeChildBySmplXml(\SimpleXMLElement $element,$atrName){
        $set = $element->children();
        if (!isset($set->$atrName)){
            return null;
        }
        return $set->$atrName;
    }
    /**
     *
     * @param \Sflib\Config\SimpleXMLElement $element
     * @param type $atrName
     * @return type
     */
    public function getNodeAttrArrayBySmplXml(\SimpleXMLElement $element){
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
    public function getNodeAttributeInt($xPath,$atrName,$failReturn = -1){
        $val = $this->getNodeAttribute($xPath, $atrName);
        if ($val === NULL || $val === false || !is_numeric($val)){
            return $failReturn;
        }
        return (int) $val;
    }

    /**
     * return node attribut value in boolean
     * @param type $xPath
     * @param type $atrName
     * @param type $failReturn
     * @return boolean
     */
    public function getNodeAttributeBool($xPath,$atrName,$failReturn = false){
        $val = $this->getNodeAttribute($xPath, $atrName);
        // string sets true/false
        if (strtolower($val) === 'false'){
            return false;
        }
        if (strtolower($val) === 'true'){
            return true;
        }
        if ($val === NULL || !is_bool($val)){

            return $failReturn;
        }
        return (bool) $val;
    }


    /**
     * parse the xml
     * @param string $data xml source
     */
    private function getData($data){
        $this->content = simplexml_load_string($data);
    }

}
