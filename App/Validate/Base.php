<?php

namespace App\Validate;

use EasySwoole\Validate\Validate;

class Base extends Validate {

    /**
     * 基础 api 校验，校验签名,
     * 随机数 + 时间戳
     */
    function __construct()
    {
        $this->addColumn('apiKey', 'apiKey')->required('apiKey字段为必须！')->lengthMin(1, 'apiKey字段为必须！');
        $this->addColumn('sercet', 'sercet')->required('sercet字段为必须！')->lengthMin(1, 'sercet字段为必须！');;
        $this->addColumn('exchange', 'exchange')->required('exchange字段为必须！')->lengthMin(1, 'exchange字段为必须！');;
    }

    /**
     * 按顺序拼接字符串，然后使用
     */
    static function sign(array $orders,array $data, $algo='md5') {
        $param_str = '';
        foreach($orders as $order) {
            if (array_key_exists($order, $data)) {
                $param_str .= $data[$order];
            } else {
                throw new \Exception("key `$order` not exist");
            }
        }
        return hash($algo, $param_str);
    }
}