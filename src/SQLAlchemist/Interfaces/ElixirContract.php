<?php

namespace Prophecy\DDriver\SQLAlchemist\Interfaces;

interface ElixirContract
{
    public function connect(string $host,int $port,string $username,string $password,string $db,string $socket = null);

    public function create(string $table,array $mappedColumnValues);

    public function read(array $conditions);

    public function update(string $table,array $conditions);

    public function delete(string $table,array $conditions);

    public function raw(string $query);
}