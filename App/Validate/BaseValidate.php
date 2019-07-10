<?php

namespace App\Validate;

class BaseValidate {

    /**
     * @param array $data 签名的数据
     * @param string $algo 签名使用的 hash 算法
     * @return string hash string
     * 按顺序拼接字符串，输入拼接字符串的 hash
     */
    /**
     * 可能出现 api key 没有权限的情况，那么在哪里处理呢？
     */
    public static function sign(array $data, $algo='md5') {
        $sign_str = '';
        ksort($data);

        foreach ($data as $value) {
            $sign_str .= $value;
        }

        return hash($algo, $sign_str);
    }
}