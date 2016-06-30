<?php
namespace golib\Types;

/**
 * Description of PropChain
 *
 * @author tziegler
 */
abstract class PropChain extends Props{

    /**
     * next propertie in chain
     * @var PropChain
     */
    private $__next = NULL;

    /**
     *
     * @param \golib\Types\PropChain $prop
     * @return \golib\Types\PropChain
     */
    public function applyProp(PropChain $prop){
        $this->__next = $prop;
        return $this->getChild();
    }

    /**
     *
     * @return \golib\Types\PropChain
     */
    public function getLastPropInChain(){
        if ($this->hasChild()){
            $prop = $this->getChild();
            while($prop->getChild()->hasChild()){
                $prop = $prop->getChild();
            }
            return $prop;
        }
        return $this;
    }


    public function hasChild(){
        return ($this->__next !== NULL && $this->__next instanceof Props);
    }

    /**
     * get the next Propertie
     * @return PropChain
     */
    public function getChild(){
        return $this->__next;
    }
}
