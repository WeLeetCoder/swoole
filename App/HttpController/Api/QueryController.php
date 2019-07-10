<?php

namespace App\HttpController\Api;

use App\Common\Code;
use App\HttpController\BaseController;
use App\Common\Query;
use App\Common\InstanceList;

class QueryController extends BaseController
{
    var $queryKey = null;
    var $params = null;
    function onRequest(?string $action): ?bool
    {
        /**
         * 此处做验证，验证数据，是否可以使用。
         * 需要一个参数解析器，从请求中解析数据，给后面使用。
         * 这里返回为 false 的时候会被拦截。
         */
        $apiKeys = $this->json();
        $this->params = $apiKeys;
        ['exchange' => $exchange, 'apiKey' => $apiKey, 'secret' => $secretKey, 'nonce' => $nonce, 'timestamp' => $timestamp] = $apiKeys;
        if (!($nonce && $timestamp)) {
            $this->writeJson(Code::PARAM_ERROR, null, '缺少时间戳以及随机数');
            return false;
        }
        $this->queryMap = InstanceList::getInstance();

        $this->queryKey = md5($exchange . $apiKey . $secretKey);

        // 判断 instanceList 中是否有这么个 key
        if ($this->queryMap->hasKey($this->queryKey)) {
            return true;
        }

        // 判断交易所是否存在
        if (Query::has_exchange($exchange)) {
            // 如果交易所存在则创建 query，并将query加入到 instanceList 中
            $query = new Query($apiKeys);
            $this->queryMap->addInstance($this->queryKey, $query);
            return true;
        }

        $this->writeJson(Code::PARAM_ERROR, null, '交易所不存在！');
        return false;
    }

    function index()
    {
        $this->reply_success($this->queryMap->getCount(), '获取成功！');
    }

    function balance(): void
    {
        $query_list = InstanceList::getInstance();
        $query = $query_list->getQuery($this->queryKey);
        $this->reply_success($query->balance(), '获取成功！');
    }

    function order()
    {
        $query_list = InstanceList::getInstance();
        $query = $query_list->getQuery($this->queryKey);
        $this->reply_success($query->load(), '获取成功！');
    }

    function ticker()
    {
        $query_list = InstanceList::getInstance();
        $query = $query_list->getQuery($this->queryKey);
        $this->send('获取成功！', $query->ticker($this->params['symbol']));
    }

    function reply_success($result = null, $msg = null)
    {
        $this->writeJson(Code::SUCCESS, $result, $msg);
    }

    function onException(\Throwable $throwable): void
    {
        $this->response()->write($throwable->getMessage());
    }
}
