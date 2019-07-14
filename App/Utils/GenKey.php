<?php

namespace App\Utils;

use EasySwoole\Component\Singleton;

class GenKey
{
    use Singleton;
    private $res;
    function genKey($alg = 'sha512', $bits = 4096, $type = OPENSSL_KEYTYPE_RSA)
    {
        $conf = [
            'digest_alg' => $alg,
            'private_key_bits' => $bits,
            'private_key_type' => $type
        ];
        $this->res = openssl_pkey_new($conf);
        return $this;
    }

    protected function __exportPrivate()
    {
        openssl_pkey_export($this->res, $privateKey);
        $this->privateKey = $privateKey;
        return $privateKey;
    }

    protected function __exportPublic()
    {
        $this->publicKey = openssl_pkey_get_details($this->res)['key'];
        return $this->publicKey;
    }

    public function exportPrivate()
    {
        return $this->__exportPrivate();
    }

    public function exportPublic()
    {
        return $this->__exportPublic();
    }

    public static function encrypt($data, $key = null, $keyType = 'public')
    {
        if (!$key) {
            return false;
        }
        if ($keyType === 'public') {
            openssl_public_encrypt($data, $encrypted, $key);
        } else {
            openssl_private_encrypt($data, $encrypted, $key);
        }
        return $encrypted;
    }

    public static function decrypt($data, $key = null, $keyType = 'private')
    {
        if (!$key) {
            return false;
        }
        if ($keyType === 'private') {
            openssl_private_decrypt($data, $decrypted, $key);
        } else {
            openssl_public_decrypt($data, $decrypted, $key);
        }
        return $decrypted;
    }
}
