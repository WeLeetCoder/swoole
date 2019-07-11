<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;
use App\Common\Code;

abstract class BaseController extends Controller
{
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
        return true;
    }

    protected function send($msg, $status, $data)
    {
        if (!$this->response()->isEndResponse()) {
            $responseBody = [
                'status' => Code::status($status),
                'ts' => round(microtime(true), 3) * 1000,
                'msg' => $msg,
                'code' => Code::msg($status),
                'data' => $data,
            ];
            
            $this->response()->withStatus(200);
            $this->response()->write(json_encode($responseBody, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            return true;
        }

        return false;
    }

    public function error($msg, $status = Code::ERROR, $data = null)
    {
        return $this->send($msg, $status ?? Code::ERROR, $data);
    }

    public function success($msg, $data = null)
    {
        return $this->send($msg, Code::SUCCESS, $data);
    }

    protected function use(\Closure $func)
    {
        return call_user_func($func, $this->request(), $this->response());
    }

    function onException(\Throwable $throwable): void
    {
        $msg = [
            'message' => $throwable->getMessage(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
        ];
        $this->error("异常捕获", null, $msg);
    }
}
