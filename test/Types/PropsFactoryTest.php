<?php

namespace Types;

use Exception;
use golib\Types\PropsFactory;
use PHPUnit\Framework\TestCase;

class PropsFactoryTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testPropsFactory() {
        $class1 = new TestPropsFactory("num", ["num" => 999],'testcase');
        $this->assertEquals(999, $class1->num);
        $this->assertEquals('num', $class1->getPrimaryKey());

    }
}


############### test-class ##########

class TestPropsFactory extends PropsFactory {
    public int $num = 1337;
    public string $prop_check = 'master';

}