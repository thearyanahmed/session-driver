<?php

namespace Prophecy\DDriver\SQLAlchemist\Elixirs;

use Prophecy\DDriver\Exceptions\{
    ConnectionException,
    InvalidColumnValueMapping,
    MySQLQueryException
};

use Prophecy\DDriver\SQLAlchemist\{
    Interfaces\ElixirContract,
    Support\Helpers
};

class Mysql implements ElixirContract
{
    /**
     * DEMO QUERY BUILDER
     */
    private  static $mappedArray = [
        'select' => '*',
        'table'  => 'sessions',
        'where' => [
            ['key','=','mew'],
            ['value','some other value']
        ],
        'limit' => [122,332]
    ];
    /**
     * DB Connection
     * @var $connection
     */
    private $connection;
    /**
     * Default comparison operator
     * @var string
     */
    private $defaultOperator = '=';

//    private $types = [
//        'where'   => 'WHERE',
//        'WHERE' => 'WHERE',
//        'or where' => 'OR WHERE',
//        'OR WHERE' => 'OR WHERE',
//        'and'     => 'AND',
//        'AND' => 'AND'
//    ];
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
     * Builds query
     * @param array $conditions
     * @return string
     */
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

    /**
     * Generates query string based on condition
     * @param array $conditions
     * @return string
     */
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

    /**
     * Decorates column values with double quotes if they are not numeric or boolean
     * @param $array
     * @return array
     */
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

    /**
     * Executes the query string
     * @param $query string
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
        //build query
        $columns = implode(', ',array_keys($mappedColumnValues));

        $values = $this->decorateColumns(array_values($mappedColumnValues));

        $values = implode(',',$values);

        $query = "INSERT INTO {$table} ( {$columns} ) VALUES ( {$values} )";
        //execute
        return $this->execute($query);
    }

    /**
     * @param array $conditions
     * @return array
     * @throws MySQLQueryException
     */
    public function read(array $conditions)
    {
        $query = $this->queryBuilder($conditions);
        $res = $this->execute($query);

        if($res->num_rows === 0) {
            return [];
        }
        return $res->fetch_assoc();
    }

    /**
     * @param string $table
     * @param array $conditions
     * @return mixed
     * @throws MySQLQueryException
     */
    public function update(string $table, array $conditions)
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
     * @throws MySQLQueryException
     */
    public function delete(string $table, array $conditions)
    {
        $query = "DELETE FROM {$table}";
        $query .= $this->conditionBuilder($conditions);
        return $this->execute($query);
    }

    /**
     * Executes raw query
     * @param string $query
     * @return mixed
     * @throws MySQLQueryException
     */
    public function raw(string $query)
    {
        return $this->execute($query);
    }
    /**
     * Closes the connection
     */
    public function close()
    {
        $this->connection->close();
    }

}