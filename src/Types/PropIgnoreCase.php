<?php

namespace golib\Types;

/**
 * Description of PropIgnoreCase
 *
 * @author tziegler
 *
 * properties that ignores case sensitive keys
 * to solve renaming issues
 */
class PropIgnoreCase extends Props
{

    public function __construct($data = NULL)
    {

        foreach ($this as $key => $dummy) {
            $this->findMatch($key, $data);
        }

        parent::__construct($data);
    }

    private function findMatch($origin, array &$data)
    {
        $lower = strtolower($origin);
        foreach ($data as $key => $value) {
            if ($lower == strtolower($key)) {
                unset($data[$key]);
                $data[$origin] = $value;
            }
        }
    }
}
