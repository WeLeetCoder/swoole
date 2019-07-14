<?php

namespace App\Utils\Encrypt;

use EasySwoole\Component\Singleton;

class Encrypt
{

    private function __construct($cipher, $passphrase, $iv = null, $opts = OPENSSL_RAW_DATA)
    {
        $this->cipher = $cipher;
        $this->passphrase = $passphrase;
        $this->init_vector = $iv;
        $this->options = $opts;
    }

    static function create(...$args) {
        return new self(...$args);
    } 

    function encrypt($data)
    {
        return openssl_encrypt($data, $this->cipher, $this->passphrase, $this->options, $this->init_vector);
    }

    function decrypt($data)
    {
        return openssl_decrypt($data, $this->cipher, $this->passphrase, $this->options, $this->init_vector);
    }
}
