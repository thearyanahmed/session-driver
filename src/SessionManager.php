<?php

namespace Prophecy\DDriver;

use Prophecy\DDriver\Exceptions\InvalidDriverImplementation;

class SessionManager {

    protected $driver;

    public function __construct(\SessionHandlerInterface $driver)
    {
        if($driver instanceof \SessionHandlerInterface === false) {
            throw new InvalidDriverImplementation;
        }

        $this->driver;
    }

}