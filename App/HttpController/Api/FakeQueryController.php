<?php

namespace App\HttpController\Api;

use App\Utils\Code;
use App\Utils\GenKey;
use Predis\Client;
use App\Common\Redis;

class FakeQueryController extends QueryController
{
    function onRequest(?string $action): ?bool
    {
        parent::onRequest($action);
        if ($this->response()->isEndResponse()) {
            return false;
        }
        return true;
    }

    function index()
    { }

    function balance(): void
    {
        parent::balance();
    }

    function order()
    { 
        parent::order();
    }
}
