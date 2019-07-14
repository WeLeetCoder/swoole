<?php

namespace App\Validate;

use App\Utils\Encrypt\Encrypt;
use EasySwoole\EasySwoole\Config;

class BaseValidate
{

    /**
     * @param array $data 签名的数据
     * @param string $algo 签名使用的 hash 算法
     * @return string hash string
     * 按顺序拼接字符串，输入拼接字符串的 hash
     */
    /**
     * 可能出现 api key 没有权限的情况，那么在哪里处理呢？
     */
    public static function sign(string $data)
    {
        $conf = Config::getInstance();
        $Encrypt = $conf->getConf('AES_ENCRYPT');
        ['AES_ALG' => $alg, 'AES_KEY' => $key, 'AES_IV' => $iv] = $Encrypt;
        return base64_encode(Encrypt::create($alg, $key, $iv)->encrypt($data));
    }

    public static function decrypt(string $data)
    {
        $conf = Config::getInstance();
        $Encrypt = $conf->getConf('AES_ENCRYPT');
        var_dump($Encrypt);
        ['AES_ALG' => $alg, 'AES_KEY' => $key, 'AES_IV' => $iv] = $Encrypt;
        return Encrypt::create($alg, $key, $iv)->decrypt($data);
    }
}
