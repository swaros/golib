<?php
namespace golib\Content\Dom;
use golib\Types\PropChain;

/**
 * Description of XmlToProps
 *
 * @author tziegler
 */
class XmlToProps extends XmlSimple{

    public function node2Props($nodeName, \SimpleXMLElement $node, PropChain $prop){
        $chainStart = true;
        foreach ($node as $name => $subNode){
            if ($name == $nodeName){
                $this->applyNodeAttributes($subNode, $prop, $chainStart);
                $chainStart =false;
            }
        }
    }

    private function applyNodeAttributes(\SimpleXMLElement $node, PropChain $prop, $asRoot = true){
        if ($asRoot){
            $prop->applyData($this->getNodeAttrArrayBySmplXml($node));
        } else {
            $chainProp = clone $prop;
            $chainProp->applyData($this->getNodeAttrArrayBySmplXml($node));
            $prop->getLastPropInChain()->applyProp($chainProp);
        }
    }

}
