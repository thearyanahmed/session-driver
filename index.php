<?php

use Prophecy\DDriver\DriverManager;
use Prophecy\DDriver\Drivers\Json;
use Prophecy\DDriver\SessionManager;

use Prophecy\DDriver\Exceptions\{
    InvalidDriverImplementation,
    DirectoryNotWriteableException
};

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$requestedDriver = getenv('SESSION_DRIVER');

try {
    $sessionDriver = (new DriverManager)->getDriver($requestedDriver);

    $session = new SessionManager($sessionDriver);
//
    $session->set('hello', 'world');
//    $session->set('test', mt_rand(1,22));
//    $session->set('users', ['some','user','list']);
//    $session->set('testing', 'pikachu');
//
    echo $session->get('hello');

} catch (\Prophecy\DDriver\Exceptions\UnsupportedDriverException $e) {
    print_r($e->getMessage());
    die();
} catch (DirectoryNotWriteableException $e) {
    print_r($e->getMessage());
    die();
} catch(InvalidDriverImplementation $e) {
    print_r($e->getMessage());
    die();
}