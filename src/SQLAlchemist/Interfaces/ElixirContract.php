<?php

namespace Prophecy\DDriver\SQLAlchemist\Interfaces;

interface ElixirContract
{
    public function connect(string $host,int $port,string $username,string $password,string $db,string $socket = null);

    public function create(array $mappedColumnValues);

    public function read(array $conditions);

    public function update(array $conditions,array $mappedColumns);

    public function delete(array $conditions);

    public function raw(string $query);
}