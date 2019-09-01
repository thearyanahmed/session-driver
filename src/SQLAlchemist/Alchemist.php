<?php

namespace Prophecy\DDriver\SQLAlchemist;

use Prophecy\DDriver\Exceptions\InvalidDriverImplementation;
use Prophecy\DDriver\SQLAlchemist\Interfaces\ElixirContract;

class Alchemist {

    private $db;

    private $defaultTable = 'sessions';

    private $columns = [
        'key'     => 'session_key',
        'value'   => 'session_value',
        'created' => 'created_at',
        'updated' => 'updated_at'
    ];
    /**
     * Alchemist constructor.
     * @param ElixirContract $db
     * @throws InvalidDriverImplementation
     */
    public function __construct(ElixirContract $db)
    {
        if($db instanceof ElixirContract === false) {
            throw new InvalidDriverImplementation;
        }

        $this->db = $db;
    }

    public function read(array $conditions)
    {
        return $this->db->read($conditions);
    }

    public function create(array $map)
    {
        return $this->db->create($this->defaultTable,$map);
    }

    public function update(array $map)
    {
        return $this->db->update($this->defaultTable,$map);
    }

    public function delete(array $map)
    {
        return $this->db->delete($this->defaultTable,$map);
    }
    public function textConnection(string $query = null)
    {
        if(!$query || $query == '') {
            $query = "SELECT * FROM {$this->defaultTable}";
        }
        return $this->db->raw($query);
    }
}