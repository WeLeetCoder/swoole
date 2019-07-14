<?php

namespace App\Validate;

use App\Utils\Code;
use EasySwoole\EasySwoole\Config;

class RequestValidate extends BaseValidate
{
    /**
     * 参数校验，需要哪些参数，哪些参数是必须的，可以列出。
     */
    /**
     * 通过 Access key 找到对应的 Secret key，然后再经过排序请求字段，签名即可
     */
    static function validate(): \Closure
    {
        /**
         * 模块是独立的吗？
         * 传输什么东西过来？做验证，然后验证完成之后怎么处理？ 验证是否通过。
         */

        return function ($controller) {

            [
                'timestamp' => $ts,
                'nonce' => $nonce,
                'signature' => $sign,
            ] = $controller->params;

            $nowTimestamp = round(microtime(true), 3) * 1000;

            /**
             * 使用当前时间戳与传过来的时间戳对比， A - B > 60 second 说明有问题
             */
            if (Config::getInstance()->getConf('MODE') === 'DEV') {
                // 开发模式，不校验时间戳。
                return true;
            }

            // 时间戳采用的是毫秒验证，所以此处，允许在60秒内的时间戳都是有效的。
            if (abs($nowTimestamp - $ts) > 60 * 1000) {
                $controller->error('时间戳错误', Code::TIME_STAMP_ERROR);
                return false;
            }
            return true;
        };
    }

    static function validateSign(): \Closure
    {
        return function ($controller) {
            $params = $controller->params;
            $sign = $params['signature'];
            unset($params['signature']);
            ksort($params);
            $signParams = [];
            foreach ($params as $key => $val) {
                array_push($signParams, "$key=$val");
            }
            $signStr = implode('&', $signParams);
            $s = parent::sign($signStr);
            if ($s !== $sign) {
                /**
                 * 开发模式，显示签名
                 */
                $controller->error('签名不正确。', Code::SIGNATURE_ERROR, rawurlencode($s));
                return false;
            }
            return true;
        };
    }

    static function requireParamsCheck(array $paramKeys = [])
    {
        return function ($controller) use ($paramKeys) {
            foreach ($paramKeys as $paramKey) {
                if (!isset($controller->params[$paramKey])) {
                    $controller->error("参数错误,缺少 $paramKey 参数。", Code::PARAM_MISS);
                    // $controller->end();
                    return false;
                }
            }
            return true;
        };
    }
}
