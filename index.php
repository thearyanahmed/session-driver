<?php

use Prophecy\DDriver\Drivers\Json;
use Prophecy\DDriver\SessionManager;

require __DIR__ . '/vendor/autoload.php';

//set driver

$sessionDriver = new Json();

$test = new SessionManager($sessionDriver);

var_dump($test);