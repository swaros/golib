<?php

namespace Types;

use golib\Types\PropIgnoreCase;
use PHPUnit\Framework\TestCase;

class PropIgnoreCaseTest extends TestCase
{

    public function test__construct()
    {
        $mastermind = new TestPropIgnoreCase(["mastermind" => "larry"]);
        $this->assertEquals("larry", $mastermind->MasterMind);
    }
}


############# test class ######

class TestPropIgnoreCase extends PropIgnoreCase {
    public string $MasterMind = "1337";
}