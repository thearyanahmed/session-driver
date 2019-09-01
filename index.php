<?php

use Prophecy\DDriver\DriverManager;
use Prophecy\DDriver\SessionManager;
use Prophecy\DDriver\Exceptions\{
    InvalidDriverImplementation,
    UnsupportedDriverException
};

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

///Get the requested session driver from env
$requestedDriver = getenv('SESSION_DRIVER');

try {
    //get the driver
    $sessionDriver = (new DriverManager)->getDriver($requestedDriver);
    //initiate the session driver
    $session = new SessionManager($sessionDriver);

    $session->set('test', mt_rand(1,22));
    $session->set('users', ['some','user','list']);
    $session->set('testing', 'pikachu');

    echo $session->get('hello');
    echo $session->get('test');

} catch (UnsupportedDriverException $e) {
    print_r($e->getMessage());
    die();
} catch(InvalidDriverImplementation $e) {
    print_r($e->getMessage());
    die();
}