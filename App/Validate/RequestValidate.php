<?php

namespace App\Validate;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class RequestValidate extends BaseValidate {
    /**
     * 参数校验，需要哪些参数，哪些参数是必须的，可以列出。
     */
    /**
     * 通过 Access key 找到对应的 Secret key，然后再经过排序请求字段，签名即可
     */
    static function validate($params = null): \Closure {
        /**
         * 模块是独立的吗？
         * 传输什么东西过来？做验证，然后验证完成之后怎么处理？ 验证是否通过。
         */

        return function (Request $request, Response $response) use ($params) {
            if ($response->isEndResponse()) {
                return false;
            }
            $timestamp = $params['timestamp'] ?? 0;
            $nonce = $params['nonce'] ?? 0;
            if ($timestamp && $nonce) {
                $nowTimestamp = time();
                if (abs($nowTimestamp - $timestamp) > 60) {
                    $response->write("timestamp is error.");
                    $response->end();
                    return false;
                } 
            }
            $response->write("must be have 2 params timestamp and nonce.");
            $response->end();
            return false;
        };
    }

    static function validateSign(): \Closure {
        return function (Request $request, Response $response) {

        };
    }
}