<?php

namespace golib\Types;

use Exception;

/**
 * Description of Timer
 *
 * @author tziegler
 */
class Timer implements Types
{

    private int $currentTime;

    const DAY = 86400;
    const HOUR = 3600;
    const MINUTE = 60;

    private bool $fitNonExistingDates;

    public function __toString()
    {
        return $this->getSqlFormat();
    }

    /**
     * Timer constructor.
     * @param null|mixed $curTime
     * @param bool $fitNonExistingDates
     * @throws Exception
     */
    public function __construct($curTime = NULL, bool $fitNonExistingDates = true)
    {
        $this->fitNonExistingDates = $fitNonExistingDates;
        if (is_string($curTime) && $this->validTimeStr($curTime)) {
            $this->setTimeBySqlTimeString($curTime);
        } else {
            $this->setTime($curTime);
        }
    }

    /**
     * set the time in milliseconds.
     * if null submitted  the current time will be used
     * @param Timer|int|string|null $curTime
     * @throws PropertyException
     */
    public function setTime(null|int|self|string $curTime = NULL)
    {

        if ($curTime == NULL) {
            $this->currentTime = time();
        } elseif ($curTime instanceof self) {
            $this->currentTime = $curTime->currentTime;
        } else {
            if (is_numeric($curTime)) {
                $this->currentTime = $curTime;
            } else {
                throw new PropertyException("Wrong Time submitted");
            }
        }
    }

    /**
     * sets time by using a DateTime formated string
     * like "1979-01-01 00:00:00"
     * @param string $timeStr
     * @throws Exception
     */
    public function setTimeBySqlTimeString(string $timeStr)
    {
        if ($this->validTimeStr($timeStr)) {
            $this->setTime(strtotime($timeStr));
        } else {
            throw new PropertyException("({$timeStr}) is not a valid time format");
        }
    }

    /**
     * gets the different from now
     * positive is past
     * negative is future
     * @return int
     */
    public function diffToNow(): int
    {
        return time() - $this->currentTime;
    }

    /**
     * gets the different from now
     * positive means time is in future
     * negativ means the time is already reached
     * @return int
     */
    public function timeLeft()
    {
        return $this->currentTime - time();
    }

    /**
     * Returns the current Time as Sql DateTime Formated String.
     * Like "2010-01-05 11:05:25"
     *
     */
    public function getSqlFormat()
    {
        $date = date('Y-m-d H:i:s', $this->currentTime);
        if ($date === '-0001-11-30 00:00:00') {
            return '0000-00-00 00:00:00';
        }
        return $date;
    }

    /**
     * returns current time
     * @return int
     */
    public function getTime()
    {
        return $this->currentTime;
    }

    /**
     * check if the string can be used for times
     * @param string $timeString
     * @return bool
     */
    private function isNonExistingTimeString(string $timeString): bool
    {
        return $timeString != date('Y-m-d H:i:s', strtotime($timeString));
    }

    /**
     * converts a date to the next possible date
     * if this date not exists
     * @param string $timeString
     * @return string
     */
    private function getPossibleDateString(string $timeString): string
    {
        return date('Y-m-d H:i:s', strtotime($timeString));
    }

    /**
     * validate an string ... only valid date string will pass
     * @param string $time formatted for Y-m-d H:i:s
     * @return bool
     */
    public function validTimeStr(string $time): bool
    {
        if ($time === '0000-00-00 00:00:00') {
            return true;
        }

        if ($this->fitNonExistingDates && $this->isNonExistingTimeString($time)) {
            $time = $this->getPossibleDateString($time);
        }

        return (
            is_string($time)
            && strlen($time) == 19
            && $time == date('Y-m-d H:i:s', strtotime($time))
        );
    }

    /**
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->getSqlFormat();
    }

    /**
     * create a new object by myself and
     * add submitted seconds
     * @param int $add
     * @return Timer
     * @throws Exception
     */
    public function cloneTimeAdd(int $add): Timer
    {
        $newTime = $this->getTime() + ($add * 1);
        return new Timer($newTime);
    }

}
