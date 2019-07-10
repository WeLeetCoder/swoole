<?php

namespace App\Common;

use EasySwoole\Component\Singleton;

class InstanceList {
    use Singleton;
    private $queryInstances = [];

    function addInstance(string $name, $instance) {
        $this->queryInstances[$name] = $instance;
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
            foreach ($this->queryInstances as $queryInstance) {
                $nowTimestamp = time();
                if (abs($nowTimestamp - $queryInstance->timestamp) > 10) {
                    $this->unset($queryInstance->queryKey);
                }
            }
        }
        // var_dump($this->queryInstances);
    }

    static function check() {
        var_dump(time() . " check Instance");
        self::getInstance()->checkInvalidRequest();
    }

    function all() {
        return $this->queryInstances;
    }
}