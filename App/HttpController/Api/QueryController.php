<?php

namespace App\HttpController\Api;

use App\Utils\Code;
use App\HttpController\BaseController;
use App\Common\Query;
use App\Common\InstanceList;
use App\Validate\RequestValidate;
use EasySwoole\EasySwoole\Config;

/**
 * 测试使用key：
 *  binance:
 *      api:    FbbaxUg46BwF1AqtL5xgV52kuTqIjw0BArKeQegkoMKMxBN3HcxgrlklSDrn92wx
 *      secret: imAw4HFCoGsCw5iHrCi2NiLoxZbzCOfDDLElSMZRRj3ekMfj0GZ0UxehtS2wAmBU
 */
class QueryController extends BaseController
{
    private $queryInstance = null;

    function onRequest(?string $action): ?bool
    {
        /**
         * 此处做验证，验证数据，是否可以使用。
         * 需要一个参数解析器，从请求中解析数据，给后面使用。
         * 这里返回为 false 的时候会被拦截。
         */
        parent::onRequest($action);
        // 解析，看看能否解析
        if ($this->response()->isEndResponse()) {
            return false;
        }

        [
            'exchange' => $exchange,
            'apiKey' => $apiKey,
            'secretKey' => $secretKey
        ] = $this->params;

        // 判断是否有该交易所
        if (!Query::has_exchange($exchange)) {
            $this->writeJson(Code::PARAM_ERROR, null, '交易所不存在！');
            $this->response()->end();
            return false;
        }

        $queryMap = InstanceList::getInstance();
        $queryKey = md5($exchange . $apiKey . $secretKey);

        // 判断是已经有了，有则直接使用，无则创建
        if ($queryMap->hasKey($queryKey)) {
            $this->queryInstance = $queryMap->getQuery($queryKey);
            $this->queryInstance->updateTimestamp();
            return true;
        }

        // 创建，并加入
        $queryInstance = Query::createQuery($this->params);
        $this->queryInstance = $queryInstance;
        $queryMap->addInstance($queryKey, $queryInstance);

        return true;
    }

    function afterAction(?string $actionName): void
    { }

    function index()
    {
        $this->success('获取成功！', InstanceList::getInstance()->getCount());
    }

    function balance(): void
    {
        /**
         * 获取余额成功返回获取成功，失败则返回失败的响应，两者不一样，不一定成功
         */
        if ($balance = $this->queryInstance->balance()) {
            $this->success('获取成功！', $balance);
        }
        else {
            $this->error('错误！');
        }
    }

    function order()
    {
        $orderId = $this->params['orderId'];
        $symbol = $this->params['symbol'];
        $this->success('获取成功！', $this->queryInstance->order($orderId, $symbol));
    }

    function orders()
    {
        $symbol = $this->params['symbol'];
        $this->success('获取成功！', $this->queryInstance->orders($symbol));
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
