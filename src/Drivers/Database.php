<?php

namespace Prophecy\DDriver\Drivers;

use Prophecy\DDriver\SQLAlchemist\Interfaces\ElixirContract;

class Database implements \SessionHandlerInterface
{
    private $connection;

    public function __construct($driver)
    {
        $this->connection = $driver;
    }

    public function close()
    {
        $this->connection->close();
    }

    public function destroy($session_id)
    {
        // TODO: Implement destroy() method.
    }

    public function gc($maxlifetime)
    {
        return true;
    }

    public function open($save_path, $name)
    {
        // TODO: Implement open() method.
    }

    public function read($session_id)
    {
        $condition = [
            'select' => '*',
            'table' => 'sessions',
            'where' => [
                ['session_key','=','mew'],
            ]
        ];
        return $this->connection->read($condition);
    }

    public function write($session_id, $session_data)
    {
        $map = ['session_key' => $session_id,'session_value' => $session_data];
        return $this->connection->create($map);
    }
}