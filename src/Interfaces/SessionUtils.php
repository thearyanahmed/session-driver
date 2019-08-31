<?php

namespace Prophecy\DDriver\Interfaces;

interface SessionUtils {
    public function unset($key) :bool;
    public function reset() : bool;
    public function all() : array;
    public function exits($key): bool;
    public function indexOf($value);
}