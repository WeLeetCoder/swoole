<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;

abstract class BaseController extends Controller {
    protected $user = [];
    function onRequest(?string $action): ?bool
    {
        /**
         * @verify：
         *  1. 解析传过来的参数
         *  2. 判断传输过来的数据中是否包含 3 个属性：
         *      1. apiKey           @required       api key
         *      2. secret           @required       secret key
         *      3. exchange         @required       交易所名称
         *      4. sign             @required       签名（保证内容完整性）
         * 
         *      sign: apiKey + secret + exchange + timestamp 
         * 
         *      
         */
        $this->response()->write('hell');
        return true;
    }

    protected function send($msg = null, $result = null, $statusCode = 0) {
        $this->writeJson($statusCode, $result, $msg);
    }
}