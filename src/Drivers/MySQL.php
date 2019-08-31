<?php

namespace Prophecy\DDriver\Drivers;

class MySQL implements \SessionHandlerInterface
{
    private $connection;

    public function __construct(
        string $host     = 'localhost',
        int $port        = 8889,
        string $username = 'root',
        string $password = 'root',
        string $db       = 'session_demo_db'
    )
    {

    }

    public function close()
    {
        // TODO: Implement close() method.
    }

    public function destroy($session_id)
    {
        // TODO: Implement destroy() method.
    }

    public function gc($maxlifetime)
    {
        // TODO: Implement gc() method.
    }

    public function open($save_path, $name)
    {
        // TODO: Implement open() method.
    }

    public function read($session_id)
    {
        // TODO: Implement read() method.
    }

    public function write($session_id, $session_data)
    {
        // TODO: Implement write() method.
    }
}