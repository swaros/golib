<?php

namespace Types;

use golib\Types\ValueSet;
use PHPUnit\Framework\TestCase;

class ValueSetTest extends TestCase
{

    public function testSelfImplode()
    {
        $test = new TestValueSet1([300,500,888]);
        $this->assertEquals("300;500;888", $test->selfImplode());

        $test = new TestValueSet1(["name" => 'susie', "cnt" => 8, "roger" => true]);
        $this->assertEquals("susie;8;1", $test->selfImplode());

        $test->applyData('gerd', false);
        $this->assertEquals("susie;8;1;gerd", $test->selfImplode());

        $test = new TestValueSet1("300;500;888");
        $this->assertEquals("300;500;888", $test->selfImplode());
    }

    public function testCountError() {
        $this->expectNotice();
        new TestValueSet1(["name" => 'susie']);
    }

    public function testToStr() {
        $this->assertEquals("just as example",(string)new TestValueSet1([300,500,888]));
    }
}

######## test classes ##############

class TestValueSet1 extends ValueSet {

    public string $name = 'lucky';

    protected function getMaxEntries(): int|null
    {
        return 3;
    }

    protected function getDelimiter(): string
    {
        return ";";
    }

    protected function formatToString(): string
    {
        return "just as example";
    }
}