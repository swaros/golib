<?php
namespace golib\Types;

/**
 * Description of Timer
 *
 * @author tziegler
 */
class Timer {
    private $currentTime;


    public function __toString() {
        return $this->getSqlFormat();
    }


    public function __construct($curTime = NULL)
    {
        if (is_string($curTime) && $this->validTimeStr($curTime)){
            $this->setTimeBySqlTimeString($curTime);
        } else {
            $this->setTime($curTime);
        }
    }

    /**
     * set the time in miliseconds.
     * if null submitted  the current time will be used
     * @param type $curTime
     * @throws Exception
     */
    public function setTime($curTime = NULL){
        if ($curTime == NULL) {
            $this->currentTime = time();
        } else {
            if (is_numeric($curTime)) {
                $this->currentTime = $curTime;
            } else {
                Throw new \Exception("Wrong Time submitted");
            }
        }
    }

    /**
     * sets time by using a DateTime formated string
     * like "1979-01-01 00:00:00"
     * @param string $timeStr
     */
    public function setTimeBySqlTimeString($timeStr){
        if ($this->validTimeStr($timeStr)){
            $this->setTime(strtotime($timeStr));
        } else {
            Throw new \InvalidArgumentException("({$timeStr}) is not a valid time format");
        }
    }

    /**
     * gets the different from now
     * positive is past
     * negativ is future
     * @return int
     */
    public function diffToNow(){
        return time() - $this->currentTime;
    }

    /**
     * gets the different from now
     * positive is past
     * negativ is future
     * @return int
     */
    public function timeLeft(){
        return $this->currentTime - time();
    }


    /**
     * Returns the current Time as Sql DateTime Formated String.
     * Like "2010-01-05 11:05:25"
     *
     */
    public function getSqlFormat()
    {
        return date('Y-m-d H:i:s', $this->currentTime);
    }

    /**
     * returns current time
     * @return int
     */
    public function getTime(){
        return $this->currentTime;
    }

    /**
     * validate an string ... only valid datestring will pass
     * @param string $time formated for Y-m-d H:i:s
     * @return boolan
     */
    public function validTimeStr($time){
        if ($time === '0000-00-00 00:00:00'){
            return true;
        }
        return (is_string($time) && strlen($time) == 19 && $time == date('Y-m-d H:i:s',strtotime($time)));
    }

}