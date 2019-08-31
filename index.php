<?php

use Prophecy\DDriver\Drivers\{Json,Redis};
use Prophecy\DDriver\Exceptions\DirectoryNotWriteableException;
use Prophecy\DDriver\Exceptions\InvalidDriverImplementation;
use Prophecy\DDriver\SessionManager;

require __DIR__ . '/vendor/autoload.php';

//set driver
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$driver = getenv('SESSION_DRIVER');

if($driver === 'json') {
    $sessionDriver = new Json();
} else if($driver === 'redis') {
    $sessionDriver = new Redis();
}
try {
    $session = new SessionManager($sessionDriver);


//    session_set_save_handler($sessionDriver, true);
//    session_start();

} catch (DirectoryNotWriteableException $e) {
    print_r($e->getMessage());
    die();
} catch(InvalidDriverImplementation $e) {
    print_r($e->getMessage());
    die();
}
