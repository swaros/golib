<?php
namespace golib\Types;

/**
 * Description of EnumException
 *
 * @author tziegler
 */
class EnumException extends \Exception {
    const Invalidvalue = 100;
    const NoEnumsDefined = 101;
}
