<?php

namespace App\HttpController\Api;

use App\Common\Code;
use App\Common\GenKey;

class FakeQueryController extends QueryController
{
    function onRequest(?string $action): ?bool
    {
        return true;
    }

    function index()
    {
        
        $this->error("测试失败");
    }

    function b(): void
    {
        GenKey::getInstance()->genKey(OPENSSL_CIPHER_AES_256_CBC);
        $en = GenKey::encrypt("fuck you mother", GenKey::getInstance()->exportPublic());
        $de = GenKey::decrypt($en, GenKey::getInstance()->exportPrivate());
        $this->success('请求成功', $de);
    }
}
