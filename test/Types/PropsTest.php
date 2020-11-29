<?php

namespace Types;

use Exception;
use golib\Types\Props;
use golib\Types\Timer;
use PHPUnit\Framework\TestCase;

class PropsTest extends TestCase
{

    /**
     * @throws Exception
     */
    public function testApplyData()
    {
        $check = new TestPropClass(['name' => 'claus']);
        $this->assertEquals('claus', $check->name);

        $check->applyData(['sureName' => 'unset']);


        $check->applyData(['name' => 'rhino']);
        $this->assertEquals('rhino', $check->name);


        $check->assignExisting('name', 1256);
        $this->assertEquals('1256', $check->name);


        $this->assertEquals(500, $check->count);
        $check->assignExisting('count', 'klaus');
        $this->assertEquals(0, $check->count);
        $check->assignExisting('count', true);
        $this->assertEquals(1, $check->count);

        $this->assertEquals('1969-01-01 00:00:00', $check->timer->getSqlFormat());
        $check->assignExisting('timer', '2020-12-12 00:00:00');
        $this->assertEquals('2020-12-12 00:00:00', $check->timer->getSqlFormat());

        $this->assertEquals(true, $check->check);
        $check->assignExisting('check', 'false');
        $this->assertEquals(false, $check->check);
        $check->assignExisting('check', 'something');
        $this->assertEquals(true, $check->check);

        $cloned = clone $check;
        $this->assertEquals($cloned, $check);

    }

    public function testPlain()
    {
        $prop = new TestPropClass();
        $this->assertEquals('santa', $prop->name);
    }
}


##### Props test classes ##########

class TestPropClass extends Props
{
    public string $name = "santa";
    public int $count = 500;
    public bool $check = true;

    public Timer $timer;

    /**
     * TestPropClass constructor.
     * @param array|object|null $data
     * @throws Exception
     */
    public function __construct(array|object $data = NULL)
    {
        $this->timer = new Timer("1969-01-01 00:00:00");
        parent::__construct($data);

    }
}

