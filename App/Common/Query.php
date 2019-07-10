<?php

namespace App\Common;

use ccxt;

class Query
{
    public $timestamp = null;
    private $queryInstance = null;
    public $queryKey = null;

    function __construct($opts = null)
    {
        var_dump('create new obj');

        [
            'exchange' => $exchange,
            'apiKey' => $apiKey,
            'secretKey' => $secretKey
        ] = $opts;
        
        $conf = [
            'apiKey' =>  $apiKey,
            'secret' =>  $secretKey,
            // // 'verbose' => true,
        ];

        if (isset($opts['password'])) {
            ['password' => $conf['password']] = $opts;
        }

        if (isset($opts['version'])) {
            ['version' => $conf['version']] = $opts;
        }

        $exchange_cls = "ccxt\\{$exchange}";
        $this->queryInstance = new $exchange_cls($conf);
        $this->queryKey = md5($exchange . $apiKey . $secretKey);
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
        return $this->queryInstance->fetch_balance();
    }

    function ticker(string $currency_name)
    {
        return $this->queryInstance->fetch_ticker($currency_name);
    }

    function order(string $currency_name)
    {
        return $this->queryInstance->fetch_order_book($currency_name);
    }

    function load()
    {
        return $this->queryInstance->loadMarkets();
    }

    function updateTimeStamp() {
        $this->timestamp = time();
    }
}
