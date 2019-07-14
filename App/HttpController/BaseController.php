<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;
use App\Utils\Code;
use App\Validate\RequestValidate;
use EasySwoole\EasySwoole\Config;

abstract class BaseController extends Controller
{
    public $params = null;
    function index()
    { }

    function onRequest(?string $action): ?bool
    {
        /**
         * 通用的拦截器，所有请求都必须通过这，否则没办法往下走。
         */
        // 如果都是需要 key 的话，可以在这里做一个判断
        // 这里验证签名，签名正确了，才能往下走.

        /**
         * 先解析参数 -> 解析了参数以后，再验证时间戳，时间戳没错
         * nonce 的作用是什么？
         */
        $request = $this->request();
        if ($request->getMethod() === 'GET') {
            $this->params = $request->getQueryParams();
        } elseif ($request->getMethod() === 'POST' || strpos($request->getHeader('content-type')[0], 'application/json') !== false) {
            $params = $this->json();
            if ($params) {
                foreach ($params as $key => $val) {
                    $params[$key] = rawurldecode($val);
                }
            }
            $this->params = $params;
        }

        /**
         * 验证参数是否存在
         */
        $validateResult = $this->use(RequestValidate::requireParamsCheck([
            'nonce',
            'timestamp',
            'signature',
        ]));

        /**
         * 不存在则停止响应，同时也要停止逻辑的继续，
         * 不能继续往下走，因为没有足够的参数。
         */
        if (!$validateResult) {
            $this->response()->end();
            return false;
        }

        /**
         * 参数有了，先验证时间戳
         * 时间戳在合法范围内，则说明ok，
         * 时间戳验证了，验证签名，签名也ok，
         * 那就往下面走。
         */
        $validateResult = $this->use(RequestValidate::validate());
        if (!$validateResult) {
            $this->response()->end();
            return false;
        }

        $validateResult = $this->use(RequestValidate::validateSign());
        if (!$validateResult) {
            $this->response()->end();
            return false;
        }

        return true;
    }

    function onException(\Throwable $throwable): void
    {
        $msg = [
            'message' => $throwable->getMessage(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            // 't' => $throwable->getTrace()
        ];

        $this->error("异常捕获", null, $msg);
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
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');

            if (Config::getInstance()->getConf('MODE') === 'DEV') {
                $this->response()->write(json_encode($responseBody, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
                return true;
            }

            $this->response()->write(json_encode($responseBody, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            return true;
        }

        return false;
    }

    /**
     * error 和 success 方法用于最终返回结果，不能多次调用。
     */
    public function error($msg, $status = Code::ERROR, $data = null)
    {
        $res = $this->send($msg, $status ?? Code::ERROR, $data);
        // $this->response()->end();
        return $res;
    }

    public function success($msg, $data = null)
    {
        $res = $this->send($msg, Code::SUCCESS, $data);
        // $this->response()->end();
        return $res;
    }

    protected function use(\Closure $func)
    {
        return call_user_func($func, $this);
    }

    // public function end() {
    //     return $this->response()->end();
    // }
}
