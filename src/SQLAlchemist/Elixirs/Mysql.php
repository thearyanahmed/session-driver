<?php

namespace Prophecy\DDriver\SQLAlchemist\Elixirs;

use Prophecy\DDriver\Exceptions\ConnectionException;
use Prophecy\DDriver\Exceptions\InvalidColumnValueMapping;
use Prophecy\DDriver\Exceptions\MySQLQueryException;
use Prophecy\DDriver\SQLAlchemist\Interfaces\ElixirContract;
use Prophecy\DDriver\SQLAlchemist\Support\Helpers;

class Mysql implements ElixirContract
{
    private $connection;

    private $defaultOperator = '=';

    private $types = [
        'where'   => 'WHERE',
        'WHERE' => 'WHERE',
        'or where' => 'OR WHERE',
        'OR WHERE' => 'OR WHERE',
        'and'     => 'AND',
        'AND' => 'AND'
    ];
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
     * @throws MySQLQueryException
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
        //execute
        return $this->execute($query);
    }

    public function read(array $conditions)
    {
        $query = $this->queryBuilder($conditions);
        $res = $this->execute($query);

        if($res->num_rows === 0) {
            return [];
        }
        return $res->fetch_assoc();
    }

    //array structure is to be expected like
    //$mappedArray = [
        //'select' => '*',
        //'table' => 'sessions',
        //'where' => [
        //['key','=','mew'],
        //['value','hukka hua']
        //],
        //'limit' => [122,332]
    //];
    private function queryBuilder(array $conditions)
    {
        $condition = '';
        if (false === Helpers::isAssoc($conditions)) {
            $condition = 'WHERE 1';

            return $condition;
        }
        //condition = ''
        foreach($conditions as $type => $values) {

            if($type === 'select' || $type === 'SELECT') {
                $condition .= "SELECT {$values}"; // select ALL/ select *
            }

            if($type === 'table' || $type === 'TABLE') {
                $condition .= " FROM {$values}";
            }

            if(is_array($values)) {
                $condition .= $this->conditionBuilder([$type => $values]);
            }
        }
        return $condition;
    }

    private function conditionBuilder(array $conditions) {
        $condition = '';
        $limiter = [];

        $types = [
            'integer', 'double', 'bool', 'array', 'object'
        ];
        $whereCounter = 0;

        foreach($conditions as $type => $values) {
            if($type === 'where' || $type === 'WHERE') {
                foreach($values as $value) {
                    $whereCounter++;

                    if(count($value) === 2) {
                        $operator = $this->defaultOperator;
                        $key = $value[0];
                        $val = $value[1];
                    } else {
                        $operator = $value[1];
                        $key      = $value[0];
                        $val      = $value[2];
                    }

                    if(!in_array(gettype($val),$types)) {
                        $val = '"'. $val . '"';
                    }

                    if($whereCounter === 1) {
                        $condition .= " WHERE";
                    } else {
                        $condition .= " AND ";
                    }
                    $condition .= " {$key} {$operator} {$val}";
                }
            }

            if($type === 'limit' || $type === 'LIMIT') {
                $limiter = [$values[0],$values[1]];
            }
        }

        if(count($limiter) === 2) {
            $condition .= " LIMIT {$limiter[0]},{$limiter[1]};";
        }
        return $condition;
    }

    public function update(string $table,array $conditions)
    {
        $query = "UPDATE {$table} SET ";

        $types = [
            'integer', 'double', 'bool', 'array', 'object'
        ];
        $counter = 1;
        $len = count($conditions['update']);

        foreach($conditions['update'] as $key => $value) {

            if(!in_array($value,$types)) {
                $value = '"'. $value . '"';
            }

            $query .= " {$key}={$value}";

            if($counter < $len) {
                $query .=',';
            }

            $counter++;
        }
        $where  = $this->conditionBuilder($conditions);

        $query .= $where;

        return $this->execute($query);
    }

    /**
     * @param string $table
     * @param array $conditions
     * @return mixed
     */
    public function delete(string $table, array $conditions)
    {
        $query = "DELETE FROM {$table}";
        $query .= $this->conditionBuilder($conditions);
        return $this->execute($query);
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function raw(string $query)
    {
        return $this->execute($query);
    }


    /**
     * @param $query
     * @return mixed
     * @throws MySQLQueryException
     */
    private function execute($query)
    {
        $res =  $this->connection->query($query);
        if($this->connection->error) {
            throw new MySQLQueryException($this->connection->error);
        }

        return $res;
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

    public function close()
    {
        $this->connection->close();
    }

}