<?php

namespace Types;

use golib\Types\ConstEnum;
use golib\Types\EnumException;
use PHPUnit\Framework\TestCase;

class ConstEnumTest extends TestCase
{

    public function test__construct()
    {
        $enumClass = new TestClassOne(TestClassOne::TYPE_THREE);
        $pArr = $enumClass->getPossibleValueArray();
        $this->assertArrayHasKey("TYPE_ONE", $pArr);
        $this->assertArrayHasKey("TYPE_TWO", $pArr);
        $this->assertArrayHasKey("TYPE_THREE", $pArr);

        $this->assertEquals('main', $pArr["TYPE_ONE"]);
        $this->assertEquals('second', $pArr["TYPE_TWO"]);
        $this->assertEquals('third', $pArr["TYPE_THREE"]);

        $this->assertEquals("third", $enumClass->getValue());

    }

    public function testErrorCase() {
        $this->expectException(EnumException::class);
        $fail = new TestClassOne();
        $fail->getValue();
    }
}

##################################### test classes
class TestClassOne extends ConstEnum
{
    const TYPE_ONE = "main";
    const TYPE_TWO = "second";
    const TYPE_THREE = "third";
}