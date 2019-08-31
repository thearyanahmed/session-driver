<?php

namespace Prophecy\DDriver\SQLAlchemist\Support;

class Helpers {
    public static function isAssoc(array &$array) : bool
    {
        if (array() === $array) return false;
        return array_keys($array) !== range(0, count($array) - 1);
    }
}