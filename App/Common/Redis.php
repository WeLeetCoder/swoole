<?php

namespace App\Common;

use Predis\Client;
use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Config;

class Redis extends Client
{
    use Singleton;
    var $conn = null;
    static function __callStatic($name, $args)
    {
        [
            'SCHEMA' => $schema,
            'HOST' => $host,
            'PORT' => $port,
        ] = Config::getInstance()->getConf('REDIS');

        if (!self::getInstance()->conn) {
            self::getInstance()->conn = new Client("$schema://$host:$port");
        }
        return self::getInstance()->conn->$name(...$args);
    }
}
