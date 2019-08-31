<?php

namespace Prophecy\DDriver\SQLAlchemist;

use Prophecy\DDriver\Exceptions\InvalidDriverImplementation;
use Prophecy\DDriver\SQLAlchemist\Interfaces\ElixirContract;

class Alchemist {
    private $db;

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

    public function textConnection()
    {
        var_dump($this->db->raw('SELECT * FROM sessions'));
    }
}