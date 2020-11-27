<?php

namespace golib\Types;

/**
 * Description of PropChain
 *
 * @author tziegler
 */
abstract class PropChain extends Props
{

    /**
     * next property in chain
     * @var PropChain|null
     */
    private ?PropChain $__next = NULL;

    /**
     *
     * @param PropChain $prop
     * @return PropChain
     */
    public function applyProp(PropChain $prop): self
    {
        $this->__next = $prop;
        return $this->getChild();
    }

    /**
     *
     * @return PropChain
     */
    public function getLastPropInChain()
    {
        if ($this->hasChild()) {
            $prop = $this->getChild();
            while ($prop->getChild()->hasChild()) {
                $prop = $prop->getChild();
            }
            return $prop;
        }
        return $this;
    }


    public function hasChild()
    {
        return ($this->__next !== NULL && $this->__next instanceof Props);
    }

    /**
     * get the next Property
     * @return PropChain
     */
    public function getChild(): self
    {
        return $this->__next;
    }
}
