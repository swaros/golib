<?php

namespace golib\Types;

/**
 * Description of Timer
 *
 * @author tziegler
 */
class Timer implements Types {

    private $currentTime;

    const DAY = 86400;
    const HOUR = 3600;
    const MINUTE = 60;

    private $fitNonExistingDates = true;

    public function __toString () {
        return $this->getSqlFormat();
    }

    public function __construct ( $curTime = NULL, $fitNonExistingDates = true ) {
        $this->fitNonExistingDates = $fitNonExistingDates;
        if (is_string( $curTime ) && $this->validTimeStr( $curTime )) {
            $this->setTimeBySqlTimeString( $curTime );
        } else {
            $this->setTime( $curTime );
        }
    }

    /**
     * set the time in miliseconds.
     * if null submitted  the current time will be used
     * @param type $curTime
     * @throws Exception
     */
    public function setTime ( $curTime = NULL ) {

        if ($curTime == NULL) {
            $this->currentTime = time();
        } elseif ($curTime instanceof self) {
            $this->currentTime = $curTime->currentTime;
        } else {
            if (is_numeric( $curTime )) {
                $this->currentTime = $curTime;
            } else {
                Throw new \Exception( "Wrong Time submitted" );
            }
        }
    }

    /**
     * sets time by using a DateTime formated string
     * like "1979-01-01 00:00:00"
     * @param string $timeStr
     */
    public function setTimeBySqlTimeString ( $timeStr ) {
        if ($this->validTimeStr( $timeStr )) {
            $this->setTime( strtotime( $timeStr ) );
        } else {
            Throw new \InvalidArgumentException( "({$timeStr}) is not a valid time format" );
        }
    }

    /**
     * gets the different from now
     * positive is past
     * negativ is future
     * @return int
     */
    public function diffToNow () {
        return time() - $this->currentTime;
    }

    /**
     * gets the different from now
     * positive means timeis in future
     * negativ means the time is already reached
     * @return int
     */
    public function timeLeft () {
        return $this->currentTime - time();
    }

    /**
     * Returns the current Time as Sql DateTime Formated String.
     * Like "2010-01-05 11:05:25"
     *
     */
    public function getSqlFormat () {
        $date = date( 'Y-m-d H:i:s', $this->currentTime );
        if ($date === '-0001-11-30 00:00:00') {
            return '0000-00-00 00:00:00';
        }
        return $date;
    }

    /**
     * returns current time
     * @return int
     */
    public function getTime () {
        return $this->currentTime;
    }

    /**
     * check if the timestring a nown date
     * @param type $timeString
     * @return type
     */
    private function isNonExistingTimeString ( $timeString ) {
        return $timeString != date( 'Y-m-d H:i:s', strtotime( $timeString ) );
    }

    /**
     * converts a date to the next possible date
     * if this date not exists
     * @param type $timeString
     * @return type
     */
    private function getPossibleDateString ( $timeString ) {
        return date( 'Y-m-d H:i:s', strtotime( $timeString ) );
    }

    /**
     * validate an string ... only valid datestring will pass
     * @param string $time formated for Y-m-d H:i:s
     * @return boolan
     */
    public function validTimeStr ( $time ) {
        if ($time === '0000-00-00 00:00:00') {
            return true;
        }

        if ($this->fitNonExistingDates && $this->isNonExistingTimeString( $time )) {
            $time = $this->getPossibleDateString( $time );
        }

        return (is_string( $time ) && strlen( $time ) == 19 && $time == date( 'Y-m-d H:i:s',
                                                                              strtotime( $time ) ));
    }

    /**
     *
     * @return string
     */
    public function getValue () {
        return $this->getSqlFormat();
    }

    /**
     * create a new object by myself and
     * add submitted seconds
     * @param type $add
     * @return \GLib\Set\Timer
     */
    public function cloneTimeAdd ( $add ) {
        $newTime = $this->getTime() + ($add * 1);
        return new Timer( $newTime );
    }

}
