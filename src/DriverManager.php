<?php

namespace Prophecy\DDriver;

use Prophecy\DDriver\Drivers\{
    Json,
    Redis,
    Database
};
use Prophecy\DDriver\Exceptions\{
    ConnectionException,
    UnsupportedDriverException
};
use Prophecy\DDriver\SQLAlchemist\{
    Alchemist,
    Elixirs\Mysql
};

class DriverManager
{
    /**
     * @var array
     */
    protected $drivers = [
        'json'     => 'initJsonDriver',
        'database' => 'initDatabaseDriver',
        'redis'    => 'initRedisDriver'
    ];

    private $env;
    /**
     * @param string $requestedDriver
     * @return mixed
     * @throws UnsupportedDriverException
     */
    public function getDriver(string $requestedDriver)
    {
        if(isset($this->drivers[$requestedDriver])) {
            return $this->{$this->drivers[$requestedDriver]}();
        } else {
            throw new  UnsupportedDriverException;
        }
    }

    /**
     * @return Json
     */
    protected function initJsonDriver()
    {
        $dir = getenv('JSON_DIR');
        return new Json($dir,mt_rand(1,100000));
    }

    /**
     * @return Database
     * @throws ConnectionException
     * @throws Exceptions\InvalidDriverImplementation
     */
    protected function initDatabaseDriver()
    {
        $dbDriver = new Mysql();

        $host     = getenv('DB_HOST');
        $port     = getenv('DB_PORT');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $db       = getenv('DB_DATABASE');
        $socket   = getenv('DB_SOCKET');

        try {
            $dbDriver->connect($host, $port, $username, $password, $db, $socket);

            $alchemist = new Alchemist($dbDriver);
            return new Database($alchemist);
        } catch (Exceptions\ConnectionException $e) {
            throw $e;
        } catch (Exceptions\InvalidDriverImplementation $e) {
            throw $e;
        }
    }

    /**
     * @return Redis
     */
    protected function initRedisDriver()
    {
        $host      = getenv('REDIS_HOST');
        $port      = getenv('REDIS_PORT');
        $enableTls = getenv('REDIS_ENABLE_TLS') == 'true';

        return new Redis($host,$port,$enableTls);
    }
}