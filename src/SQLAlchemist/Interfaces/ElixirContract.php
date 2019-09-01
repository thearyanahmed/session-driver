<?php

namespace Prophecy\DDriver\SQLAlchemist\Interfaces;

interface ElixirContract
{
    /**
     * @param string $host
     * @param int $port
     * @param string $username
     * @param string $password
     * @param string $db
     * @param string|null $socket
     * @return mixed
     */
    public function connect(string $host, int $port, string $username, string $password, string $db, string $socket = null);

    /**
     * @param string $table
     * @param array $mappedColumnValues
     * @return mixed
     */
    public function create(string $table, array $mappedColumnValues);

    /**
     * @param array $conditions
     * @return mixed
     */
    public function read(array $conditions);

    /**
     * @param string $table
     * @param array $conditions
     * @return mixed
     */
    public function update(string $table, array $conditions);

    /**
     * @param string $table
     * @param array $conditions
     * @return mixed
     */
    public function delete(string $table, array $conditions);

    /**
     * @return mixed
     */
    public function close();

    /**
     * @param string $query
     * @return mixed
     */
    public function raw(string $query);
}