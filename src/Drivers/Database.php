<?php

namespace Prophecy\DDriver\Drivers;

use Prophecy\DDriver\SQLAlchemist\Interfaces\ElixirContract;

class Database implements \SessionHandlerInterface
{
    /**
     * connection to database
     * @var $connection
     */
    private $connection;

    /**
     * Database constructor.
     * @param $driver
     */
    public function __construct($driver)
    {
        $this->connection = $driver;
    }

    /**
     * @param string $save_path
     * @param string $name
     * @return bool
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * @param string $session_id
     * @param string $session_data
     * @return bool
     */
    public function write($session_id, $session_data)
    {
        $map = ['session_key' => $session_id,'session_value' => $session_data];
        return $this->connection->create($map);
    }

    /**
     * @param string $session_id
     * @return string
     */
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

    /**
     * @param string $session_id
     * @return bool
     */
    public function destroy($session_id)
    {
        $cond = [
            'where' => [
                ['session_key','=',$session_id]
            ]
        ];

        return $this->connection->delete($cond);
    }

    /**
     * @param int $maxlifetime
     * @return bool
     */
    public function gc($maxlifetime)
    {
        //replicate delete all from where created_at < $maxlifetime
        return true;
    }

    /**
     * @return bool|void
     */
    public function close()
    {
        $this->connection->close();
    }
}