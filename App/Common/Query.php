<?php

namespace App\Common;

use ccxt;

class Query
{
    public $timestamp = null;
    private $queryInstance = null;

    function __construct($opts)
    {

        $conf = [
            'apiKey' => $opts['apiKey'],
            'secret' => $opts['secretKey'],
            'userAgent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) snap Chromium/75.0.3770.100 Chrome/75.0.3770.100 Safari/537.36',
            // 'verbose' => true,
            'enableRateLimit' => true,
        ];

        $reParams = [
            'password',
            'version',
        ];

        
        foreach ($reParams as $paramName) {
            if (isset($opts[$paramName])) {
                $conf[$paramName] = $opts[$paramName];
            }
        }

        /**
         * 创建对象
         */
        $exchange_cls = "ccxt\\{$opts['exchange']}";
        $this->queryInstance = new $exchange_cls($conf);
        $this->updateTimeStamp();
    }

    static function createQuery($params = null)
    {
        return new self($params);
    }

    static function has_exchange(string $exchange)
    {
        return in_array($exchange, ccxt\Exchange::$exchanges);
    }

    function balance()
    {
        $data = $this->queryInstance->fetch_balance();
        var_dump($data);
        unset($data['info']);
        return $data;
    }

    function ticker(string $symbol)
    {
        return $this->queryInstance->fetch_ticker($symbol);
    }

    function order($id, string $symbol)
    {
        return $this->queryInstance->fetch_order($id, $symbol);
    }

    function orders(string $symbol)
    {

        return $this->queryInstance->fetch_orders($symbol);
    }

    function load()
    {
        return $this->queryInstance->load_markets();
    }

    function updateTimeStamp()
    {
        $this->timestamp = time();
    }
}
