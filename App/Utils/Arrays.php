<?php

namespace App\Utils;

class Arrays {
    private $arr = [];
    function __construct(?array $arr)
    {
        if ($arr) {
            $this->arr = $arr;
        }
    }

    function includes($value) {
        foreach (array_values($this->arr) as $val) {
            if ($value === $val) {
                return true;
            }
        }
        return false;
    }

    function hasKey($key) {
        return array_key_exists($key, $this->arr);
    }

    static function New(array $arr = null) {
        var_dump($arr);
        return new self($arr);
    }

    function __get($name)
    {
        if (isset($this->arr[$name])) {
            return $this->arr[$name];
        }
        return NULL;
    }

    function __set($name, $value)
    {
        $this->arr[$name] = $value;
    }

    function length() {
        return count($this->arr);
    }

    function real() {
        return $this->arr;
    }
}