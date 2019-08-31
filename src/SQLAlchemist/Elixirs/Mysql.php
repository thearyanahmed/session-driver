<?php

namespace Prophecy\DDriver\SQLAlchemist\Elixirs;

use Prophecy\DDriver\Exceptions\ConnectionException;
use Prophecy\DDriver\SQLAlchemist\Interfaces\ElixirContract;

class Mysql implements ElixirContract
{
    private $connection;

    /**
     * @param string $host
     * @param int $port
     * @param string $username
     * @param string $password
     * @param string $db
     * @param string|null $socket
     * @throws ConnectionException
     */
    public function connect(string $host, int $port, string $username, string $password, string $db, string $socket = null)
    {
        $connection = new \mysqli($host,$username,$password,$db,$port,$socket);

        if($connection->connect_error) {
            throw new ConnectionException('Sorry,Could not connect to database using provided credentials');
        }

        $this->connection = $connection;
    }

    public function create(array $mappedColumnValues)
    {
        // TODO: Implement create() method.
    }

    public function read(array $conditions)
    {
        // TODO: Implement read() method.
    }

    public function update(array $conditions, array $mappedColumns)
    {
        // TODO: Implement update() method.
    }

    public function delete(array $conditions)
    {
        // TODO: Implement delete() method.
    }

    public function raw(string $query)
    {
        return $this->connection->query($query);
    }
}