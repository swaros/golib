<?php

namespace Types;

use golib\Types\Enum;
use golib\Types\EnumException;
use PHPUnit\Framework\TestCase;

class EnumTest extends TestCase
{

    public function testGetValueFailure()
    {
        $this->expectException(EnumException::class);
        new TestCl1("leon");
    }

    public function test__construct()
    {
        $class = new TestCl1("tiger");
        $this->assertInstanceOf(TestCl1::class, $class);
    }

    public function test__toString()
    {
        $class = new TestCl1("tiger");
        $this->assertEquals("tiger", $class);
    }

    public function testGetPossibleValueArrayAndValue()
    {
        $class = new TestCl1("tiger");
        $this->assertInstanceOf(TestCl1::class, $class);
        $this->assertEquals('tiger',$class->getPossibleValueArray()[0]);
        $this->assertEquals("tiger", $class->getValue());
    }
}

####################### test class(es) ##########

class TestCl1 extends Enum {

    function getPossibleValueArray()
    {
        return [
            "tiger"
        ];
    }
}