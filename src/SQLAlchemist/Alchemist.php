<?php

namespace Prophecy\DDriver\SQLAlchemist;

use Prophecy\DDriver\Exceptions\InvalidDriverImplementation;
use Prophecy\DDriver\SQLAlchemist\Interfaces\ElixirContract;

class Alchemist {
    /**
     * @var ElixirContract
     */
    private $db;
    /**
     * Table
     * @var string
     */
    private $defaultTable = 'sessions';

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

    /**
     * @param array $map
     * @return mixed
     */
    public function create(array $map)
    {
        return $this->db->create($this->defaultTable,$map);
    }

    /**
     * @param array $conditions
     * @return mixed
     */
    public function read(array $conditions)
    {
        return $this->db->read($conditions);
    }

    /**
     * @param array $map
     * @return mixed
     */
    public function update(array $map)
    {
        return $this->db->update($this->defaultTable,$map);
    }

    /**
     * @param array $map
     * @return mixed
     */
    public function delete(array $map)
    {
        return $this->db->delete($this->defaultTable,$map);
    }

    /**
     * @param string|null $query
     * @return mixed
     */
    public function textConnection(string $query = null)
    {
        if(!$query || $query == '') {
            $query = "SELECT * FROM {$this->defaultTable}";
        }
        return $this->db->raw($query);
    }
}