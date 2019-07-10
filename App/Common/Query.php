<?php

namespace App\Common;

use ccxt;

class Query
{
    private $query_instance = null;
    public $exchange = null;
    public $apiKey = null;
    public $secret = null;
    function __construct($opts = null)
    {

        $this->exchange = $opts['exchange'];
        $this->apiKey =  $opts['apiKey'];
        $this->secret =  $opts['secret'];
        $conf = [
            'apiKey' => $this->apiKey,
            'secret' =>  $this->secret,
        ];
        if ($opts['password']) {
            ['password' => $conf['password']] = $opts;
        }

        if ($opts['version']) {
            ['version' => $conf['version']] = $opts;
        }

        $exchange_cls = "ccxt\\{$this->exchange}";
        $this->query_instance = new $exchange_cls($conf);
    }

    static function has_exchange(string $exchange) {
        return in_array($exchange, ccxt\Exchange::$exchanges);
    }

    function balance()
    {
        return $this->query_instance->fetch_balance();
    }

    function ticker(string $currency_name)
    {
        return $this->query_instance->fetch_ticker($currency_name);
    }

    function order(string $currency_name)
    {
        return $this->query_instance->fetch_order_book($currency_name);
    }

    function load() {
        return $this->query_instance->loadMarkets();
    }
}
