<?php

namespace Prophecy\DDriver;

use Prophecy\DDriver\Exceptions\InvalidDriverImplementation;

class SessionManager {

    protected $driver;

    /**
     * SessionManager constructor.
     * @param \SessionHandlerInterface $driver
     * @throws InvalidDriverImplementation
     */
    public function __construct(\SessionHandlerInterface $driver)
    {
        if($driver instanceof \SessionHandlerInterface === false) {
            throw new InvalidDriverImplementation;
        }

        $this->driver = $driver;
    }

    public function set($key,$value)
    {
        return $this->driver->write($key,$value);
    }

    public function get($key)
    {
        return $this->driver->read($key);
    }

    public function destroy($session)
    {
        return $this->driver->destroy($session);
    }
    
}