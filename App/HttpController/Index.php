<?php
namespace App\HttpController;

use App\HttpController\BaseController;

class Index extends BaseController {
    function index() {
        // $api_key = '44MUGkimQfI6S31a3MGHm3ZWeh9Q012kaBMLhYoIzedh3OBnqDl2WB5YFOtEqqbS';
        // $secret_key = 'IaDJHMkjqzXCumx1PxCGk1aE22rDcSWGVXxlLmqlblKMp6bNUMLxPpTUC12UkBte';
        // $name = 'binance';
        // $cls = "\\ccxt\\$name";
        // $ex = new ccxt\binance([
        //     'apiKey' => $api_key,
        //     'secret' => $secret_key,
        //     'timeout' => 30000,
        //     'enableRateLimit' => true,
        // ]);
        // $this->response()->withHeader('content-type', 'application/json');
        // $this->response()->write('hello');
        $this->response()->write('eo');
    }
}