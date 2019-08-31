<?php

namespace Prophecy\DDriver\SQLAlchemist\Elixirs;

use Prophecy\DDriver\Exceptions\ConnectionException;
use Prophecy\DDriver\Exceptions\InvalidColumnValueMapping;
use Prophecy\DDriver\SQLAlchemist\Interfaces\ElixirContract;
use Prophecy\DDriver\SQLAlchemist\Support\Helpers;

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

    /**
     * @param string $table
     * @param array $mappedColumnValues
     * @return mixed
     * @throws InvalidColumnValueMapping
     */
    public function create(string $table, array $mappedColumnValues)
    {
        //check if its an associative array
        if(false === Helpers::isAssoc($mappedColumnValues)) {
            //if not?invalid key value mapping
            throw new InvalidColumnValueMapping('Invalid column mapped.Must be key value pair as column_name => value');
        }
        //yes?
        //build query
        $columns = implode(', ',array_keys($mappedColumnValues));

        $values = $this->decorateColumns(array_values($mappedColumnValues));

        $values = implode(',',$values);

        $query = "INSERT INTO {$table} ( {$columns} ) VALUES ( {$values} )";
        print_r($query . " \n");
        //execute
        return $this->execute($query);
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
        return $this->execute($query);
    }

    private function execute($query)
    {
        return $this->connection->query($query) ? TRUE : $this->connection->error;
    }

    private function decorateColumns($array)
    {
        $values = array_map(function($item) {
            $types = [
                'integer', 'double', 'bool', 'array', 'object'
            ];
            if (!in_array(gettype($item), $types)) {
                return '"'. $item . '"';
            }
            return $item;
        },array_values($array));

        return $values;
    }
}