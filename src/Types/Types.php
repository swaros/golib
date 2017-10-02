<?php

namespace golib\Types;

/**
 * base interface for all golib base Types
 * @author tziegler
 */
interface Types {

    /**
     * any type class must declare a public function
     * that delivers the current stored value
     */
    public function getValue ();
}
