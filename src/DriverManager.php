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
     * supported drivers and their initiate methods
     */
    protected $drivers = [
        'json'     => 'initJsonDriver',
        'database' => 'initDatabaseDriver',
        'redis'    => 'initRedisDriver'
    ];

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
     * Initiates JSON driver
     * uses env
     * @return Json Driver
     */
    protected function initJsonDriver()
    {
        $dir = getenv('JSON_DIR');
        return new Json($dir,mt_rand(1,100000));
    }

    /**
     * Initiates Database driver
     * uses env
     * @return Database Driver
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
     * Initiates Database driver
     * uses env
     * @return Redis Driver
     */
    protected function initRedisDriver()
    {
        $host      = getenv('REDIS_HOST');
        $port      = getenv('REDIS_PORT');
        $enableTls = getenv('REDIS_ENABLE_TLS') == 'true';

        return new Redis($host,$port,$enableTls);
    }
}