<?php

namespace Prophecy\DDriver\Exceptions;
use \Exception;

class UnsupportedDriverException extends Exception {

    public function __construct($message ='Driver not supported' , $code = 0, Exception $previous = null) {
        // some code

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}