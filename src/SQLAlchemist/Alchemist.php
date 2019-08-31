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
    public function textConnection()
    {
//        $mappedArray = [
//            $this->columns['key']     => 'mew',
//            $this->columns['value']   => 'hello',
//            $this->columns['created'] => date("Y/m/d"),
//            $this->columns['updated'] => date('Y/m/d'),
//        ];
        $mappedArray = [
            'select' => '*',
            'table' => 'sessions',
            'where' => [
                ['id','=',1],
            ],
            'update' => [
                'id' => 1,
                'session_key' => 'hehe'
            ]
        ];
        $res= $this->db->update('sessions',$mappedArray);
        var_dump(($res));
//        var_dump($this->db->raw('SELECT * FROM sessions'));
    }
}