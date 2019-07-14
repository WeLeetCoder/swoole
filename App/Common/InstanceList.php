<?php

namespace App\Common;

use EasySwoole\Component\Singleton;

class InstanceList {
    use Singleton;
    private $queryInstances = [];
    private $symbols = [];
    var $a = 100;
    function addInstance(string $name, $instance) {
        $this->queryInstances[$name] = $instance;
    }

    function addSymbol($exchange, $symbol) {
        // Todo 所有的 symbol，不同交易所的 symbol 
    }

    function getCount() {
        return count($this->queryInstances);
    }

    function getQuery(string $key) {
        return $this->queryInstances[$key];
    }

    function hasKey(string $key) {
        return array_key_exists($key, $this->queryInstances);
    }

    function unset(string $key) {
        unset($this->queryInstances[$key]);
    }

    function checkInvalidRequest() {
        /**
         * 检查时间戳
         */
        if ($this->getCount() > 0) {
            foreach ($this->queryInstances as $queryKey => $queryInstance) {
                $nowTimestamp = time();
                if (abs($nowTimestamp - $queryInstance->timestamp) > 10) {
                    $this->unset($queryKey);
                }
            }
        }
    }

    function set($val) {
        $this->a = $val;
    }

    function get() {
        return $this->a;
    }
    
    static function check() {
        // var_dump(self::getInstance()->all());
    }

    function all() {
        $instance = $this->queryInstances;
        $instance['timestamp'] = time();
        return $instance;
    }
}