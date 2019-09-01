<?php

namespace Prophecy\DDriver\Interfaces;
/**
 * ------------------------------
 * Unused Interface
 * Will be used in next version
 * ------------------------------
 * Interface SessionUtils
 * @package Prophecy\DDriver\Interfaces
 */
interface SessionUtils {
    /**
     * Unset's a specific value
     * @param $key
     * @return bool
     */
    public function unset($key) :bool;

    /**
     * Resets all
     * @return bool
     */
    public function reset() : bool;

    /**
     * Return's all
     * @return array
     */
    public function all() : array;

    /**
     * Check if any value exists against the given key
     * @param $key
     * @return bool
     */
    public function exits($key): bool;

    /**
     * Search's through the values and returns the key
     * @param $value
     * @return mixed
     */
    public function indexOf($value);
}