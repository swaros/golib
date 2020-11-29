<?php

namespace Types;

use Exception;
use golib\Types\PropertyException;
use golib\Types\Timer;
use PHPUnit\Framework\TestCase;

class TimerTest extends TestCase
{

    public function test__toString()
    {
        $timer = new Timer("2020-12-20 00:21:10");
        $this->assertEquals("2020-12-20 00:21:10", (string)$timer);
    }

    /**
     * @throws Exception
     */
    public function testSetTimeBySqlTimeString()
    {
        $timer = new Timer("2020-12-20 00:21:10");
        $timer->setTimeBySqlTimeString("1980-10-20 13:45:07");
        $this->assertEquals("1980-10-20 13:45:07", (string)$timer);
    }

    /**
     * @throws Exception
     */
    public function testSetTimeBySqlTimeStringFail()
    {
        $this->expectExceptionMessage("(klaus) is not a valid time format");
        $this->expectException(PropertyException::class);
        $timer = new Timer("2020-12-20 00:21:10", false);
        $timer->setTimeBySqlTimeString("klaus");
    }


    public function testDiffToNow()
    {
        $timer = new Timer();
        sleep(1);
        $diff = $timer->diffToNow();
        $this->assertGreaterThan(0, $diff);
    }

    /**
     * @throws Exception
     */
    public function testGetTime()
    {
        $time = time();
        $timer = new Timer($time);
        $this->assertEquals($time, $timer->getTime());
    }

    /**
     * @throws Exception
     */
    public function testSetTime()
    {
        $time = 1606637560;
        $timer = new Timer();

        $this->assertGreaterThan($time, $timer->getTime());
        $timer->setTime($time);
        $this->assertEquals($time, $timer->getTime());

    }

    /**
     * @throws Exception
     */
    public function testGetValue()
    {
        $time = 1606637560;
        $timer = new Timer($time);
        $this->assertEquals("2020-11-29 08:12:40", $timer->getValue());
    }

    /**
     * @throws Exception
     */
    public function testCloneTimeAdd()
    {
        $time = 1606637560;
        $timer = new Timer($time);
        $clone = $timer->cloneTimeAdd(3600);
        $this->assertEquals("2020-11-29 09:12:40", $clone->getValue());
    }

    /**
     * @throws Exception
     */
    public function test__construct()
    {
        $time = 1606637560;
        $this->assertInstanceOf(Timer::class, new Timer());

        $timer = new Timer($time);
        $this->assertEquals($time, $timer->getTime());

        $timer = new Timer("2020-11-29 08:12:40");
        $this->assertEquals($time, $timer->getTime());

        $timer = new Timer("1606637560", false);
        $this->assertEquals($time, $timer->getTime());
    }

    public function test__constructFail()
    {
        $this->expectException(PropertyException::class);
        $this->expectExceptionMessage("Wrong Time submitted");
        new Timer("not a time string", false);
    }

    /**
     * @throws Exception
     */
    public function testValidTimeStr()
    {
        $timer = new Timer(time(),false);
        $this->assertFalse($timer->validTimeStr("chuck norris"));
        $this->assertFalse($timer->validTimeStr("2020-02-30 09:12:40"));
        $this->assertTrue($timer->validTimeStr("2020-02-20 09:12:40"));
        $this->assertTrue($timer->validTimeStr("0000-00-00 00:00:00"));

        $timer = new Timer(time());
        $this->assertTrue($timer->validTimeStr("2020-02-20"));
        $this->assertTrue($timer->validTimeStr("word"));
    }

    /**
     * @throws Exception
     */
    public function testGetSqlFormat()
    {
        $time = 1606637560;
        $timer = new Timer($time);
        $this->assertEquals("2020-11-29 08:12:40", $timer->getSqlFormat());

        // special case
        $timer = new Timer(-1);
        $this->assertEquals("1969-12-31 23:59:59", $timer->getSqlFormat());
    }

    /**
     * @throws Exception
     */
    public function testTimeLeft()
    {
        $timer = new Timer();
        $future = $timer->cloneTimeAdd(3600);
        $this->assertGreaterThan(3590,$future->timeLeft());
    }
}
