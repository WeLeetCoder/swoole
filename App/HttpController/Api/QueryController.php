<?php

namespace App\HttpController\Api;

use App\Common\Code;
use App\HttpController\BaseController;
use App\Common\Query;
use App\Common\InstanceList;
use App\Common\Parser;
use App\Validate\RequestValidate;
use EasySwoole\EasySwoole\Config;
use App\Common\GenKey;

class QueryController extends BaseController
{
    private $queryInstance = null;
    public $params = null;

    function onRequest(?string $action): ?bool
    {
        /**
         * 此处做验证，验证数据，是否可以使用。
         * 需要一个参数解析器，从请求中解析数据，给后面使用。
         * 这里返回为 false 的时候会被拦截。
         */

        /**
         * Parser => Validate => ProcessRequest
         */

        parent::onRequest($action);
        $requiredParams = ['exchange', 'apiKey', 'secretKey'];

        // 解析，看看能否解析
        $this->params = $this->use(Parser::queryString($requiredParams));
        // $this->use(RequestValidate::validate($this->params));

        if ($this->response()->isEndResponse()) {
            return false;
        }

        [
            'exchange' => $exchange,
            'apiKey' => $apiKey,
            'secretKey' => $secretKey
        ] = $this->params;

        var_dump("->", $this->params);

        // 判断是否有该交易所
        if (!Query::has_exchange($exchange)) {
            $this->writeJson(Code::PARAM_ERROR, null, '交易所不存在！');
            return false;
        }

        $queryMap = InstanceList::getInstance();
        $queryKey = md5($exchange . $apiKey . $secretKey);

        // 判断是已经有了，有则直接使用，无则创建
        if ($queryMap->hasKey($queryKey)) {
            $this->queryInstance->updateTimestamp();
            $this->queryInstance = $queryMap->getQuery($queryKey);
            return true;
        }

        // 创建，并加入
        $queryInstance = Query::createQuery($this->params);
        $queryMap->addInstance($queryKey, $queryInstance);
        $this->queryInstance = $queryMap->getQuery($queryKey);

        return true;
    }

    function index()
    {
        $this->success('获取成功！', InstanceList::getInstance()->getCount());
    }

    function balance(): void
    {
        $this->success('获取成功！', $this->queryInstance->balance());
    }

    function order()
    {
        $this->success('获取成功！', $this->queryInstance->order($this->params['symbol']));
    }


    function onException(\Throwable $throwable): void
    {
        $conf = Config::getInstance();
        if ($conf->getConf('MODE') === 'DEV') {
            parent::onException($throwable);
            return;
        }

        $errorMsg = $throwable->getMessage();
        $spaceIndex = strpos($errorMsg, " ");
        $msg = json_decode(substr($errorMsg, $spaceIndex + 1), true);
        $msg['exchange'] = substr($errorMsg, 0, $spaceIndex);
        $this->error($msg);
    }
}
