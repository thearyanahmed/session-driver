<?php

use Prophecy\DDriver\Drivers\Json;
use Prophecy\DDriver\Exceptions\DirectoryNotWriteableException;
use Prophecy\DDriver\Exceptions\InvalidDriverImplementation;
use Prophecy\DDriver\SessionManager;

require __DIR__ . '/vendor/autoload.php';

//set driver

$sessionDriver = new Json();

try {
    $session = new SessionManager($sessionDriver);

//    $session->clean();

} catch (DirectoryNotWriteableException $e) {
    print_r($e->getMessage());
    die();
} catch(InvalidDriverImplementation $e) {
    print_r($e->getMessage());
    die();
}
