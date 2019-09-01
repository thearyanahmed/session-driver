<?php

namespace Prophecy\DDriver\Exceptions;
use \Exception;

class UnsupportedDriverException extends Exception {

    /**
     * UnsupportedDriverException constructor.
     * @param string $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message ='Driver not supported' , $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}