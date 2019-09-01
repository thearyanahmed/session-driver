<?php

namespace Prophecy\DDriver\SQLAlchemist\Support;

class Helpers {
    /**
     * Checks if array is associated or one dimensional
     * @param array $array
     * @return bool
     */
    public static function isAssoc(array &$array) : bool
    {
        if (array() === $array) return false;
        return array_keys($array) !== range(0, count($array) - 1);
    }
}