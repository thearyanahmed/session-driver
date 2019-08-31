<?php

use Prophecy\DDriver\Drivers\{Database, Json, Redis};
use Prophecy\DDriver\Exceptions\DirectoryNotWriteableException;
use Prophecy\DDriver\Exceptions\InvalidDriverImplementation;
use Prophecy\DDriver\SessionManager;
use Prophecy\DDriver\SQLAlchemist\Alchemist;
use Prophecy\DDriver\SQLAlchemist\Elixirs\Mysql;

require __DIR__ . '/vendor/autoload.php';

//set driver
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$driver = getenv('SESSION_DRIVER');

if($driver === 'json') {
    $sessionDriver = new Json();
} else if($driver === 'redis') {
    $sessionDriver = new Redis();
} else if($driver === 'database') {
    $dbDriver = new Mysql();

    try {
        $dbDriver->connect(
            '127.0.0.1',
            8889,
            'root',
            'root',
            'db_elixir_test'
        );
        $alchemist = new Alchemist($dbDriver);
        $sessionDriver = new Database($alchemist);

    } catch (\Prophecy\DDriver\Exceptions\ConnectionException $e) {
//        var_dump($e);
        die();
    } catch (InvalidDriverImplementation $e) {

    } catch (\Exception $e) {
//        var_dump($e->getMessage());
        die();
    }
}

try {
    $session = new SessionManager($sessionDriver);
    $session->set('hello','world');
    echo $session->get('hello');
//    session_set_save_handler($sessionDriver, true);
//    session_start();

} catch (DirectoryNotWriteableException $e) {
    print_r($e->getMessage());
    die();
} catch(InvalidDriverImplementation $e) {
    print_r($e->getMessage());
    die();
}
