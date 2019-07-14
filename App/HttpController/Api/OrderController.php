<?php

namespace App\HttpController\Api;

use App\HttpController\BaseController;
use App\Common\Redis;
use App\Validate\RequestValidate;

class OrderController extends BaseController {
    function onRequest(?string $action): ?bool
    {
        parent::onRequest($action);
        // 看看基础验证是否有问题
        if ($this->response()->isEndResponse()) {
            return false;
        }

        return true;
    }

    function add() {
        // 传参。
        // $this->success(Redis::lrange('order:queue', 0, -1));
        $validResult = $this->use(RequestValidate::requireParamsCheck(['order']));
        if  (!$validResult) {
            return false;
        }
        $type = $this->request()->getQueryParam('type');
        $side = $this->request()->getQueryParam('side');
    }

    function create(string $type, string $symbol, $amount, $price) {
        /**
         * 创建订单
         */
    }

    function cancel() {
        /**
         * 取消订单，需要延迟执行吗？
         */
    }
}