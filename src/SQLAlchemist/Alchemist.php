<?php

namespace Prophecy\DDriver\SQLAlchemist;

use Prophecy\DDriver\Exceptions\InvalidDriverImplementation;
use Prophecy\DDriver\SQLAlchemist\Interfaces\ElixirContract;

class Alchemist {

    private $db;

    private $columns = [
        'key'   => 'session_key',
        'value' => 'session_value',
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



    public function textConnection()
    {
        $mappedArray = [
            $this->columns['key']     => 'mew',
            $this->columns['value']   => 'hello',
            $this->columns['created'] => date("Y/m/d"),
            $this->columns['updated'] => date('Y/m/d'),
        ];
        return $this->db->create('sessions',$mappedArray);
//        var_dump($this->db->raw('SELECT * FROM sessions'));
    }
}