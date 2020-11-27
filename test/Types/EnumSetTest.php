<?php

namespace Types;

use golib\Types\EnumException;
use golib\Types\EnumSet;
use PHPUnit\Framework\TestCase;

class EnumSetTest extends TestCase
{
    /**
     * @throws EnumException
     */
    public function test__construct()
    {
        $enum = new TestEnumSet(["set_b"]);
        $this->assertInstanceOf(EnumSet::class, $enum);
    }

    /**
     * @throws EnumException
     */
    public function testGetValue()
    {
        $enum = new TestEnumSet(["set_b"]);
        $value = $enum->getValue();
        $this->assertEquals("set_b",$value[0]);

    }

    /**
     * @throws EnumException
     */
    public function test__toString()
    {
        $enum = new TestEnumSet(["set_b"]);
        $enum2 = new TestEnumSet(["set_b","set_a"]);
        $this->assertEquals("set_b,set_a",(string)$enum2);
        $this->assertEquals("set_b",(string)$enum);
    }
}

################### test class ##############

class TestEnumSet extends EnumSet {

    function getPossibleValueArray()
    {
        return ["set_a","set_b"];
    }
}