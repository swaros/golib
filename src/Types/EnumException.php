<?php
namespace golib\Types;

use Exception;

/**
 * Description of EnumException
 *
 * @author tziegler
 */
class EnumException extends Exception {
    const InvalidValue = 100;
    const NoEnumsDefined = 101;
}
