<?php

namespace App\Common;

use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class Parser
{
    /**
     * 用来解析传输过来的参数，传过来的参数需要有哪些东西？
     */
    public static function requiredParamsCheck(array $requiredParams, $params, Response $response)
    {
        if (!count($params)) {
            $response->write("require params.");
            $response->end();
            return false;
        }
        foreach ($requiredParams as $requiredParam) {
            if (!isset($params[$requiredParam]) || $params[$requiredParam] === '') {
                $response->write("neede param `$requiredParam` not exist.");
                $response->withStatus(400);
                $response->end();
                return false;
            }
        }
        return true;
    }

    static function rawString(array $requiredParams = [], $check = false)
    {
        return function (Request $request, Response $response) use ($requiredParams, $check) {
            $params = json_decode($request->getBody()->__toString(), true) ?: [];
            if ($check) {
                self::requiredParamsCheck($requiredParams, $params, $response);
            }
            return $params;
        };
    }

    static function queryString(array $requiredParams = [], $check = false)
    {
        return function (Request $request, Response $response) use ($requiredParams, $check) {
            $params = $request->getQueryParams();
            if ($check) {
                self::requiredParamsCheck($requiredParams, $params, $response);
            }
            return $params;
        };
    }

    static function requestParams() {
        return 
    }
}
