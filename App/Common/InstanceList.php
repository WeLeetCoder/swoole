<?php

namespace App\Common;

use EasySwoole\Component\Singleton;

class InstanceList {
    use Singleton;
    private $QueryInstance = [];

    function addInstance(string $name, $instance) {
        $this->QueryInstance[$name] = $instance;
    }

    function getCount() {
        return count($this->QueryInstance);
    }

    function getQuery(string $key) {
        return $this->QueryInstance[$key];
    }

    function hasKey(string $key) {
        return array_key_exists($key, $this->QueryInstance);
    }
}