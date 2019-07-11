<?php

namespace App\Common;

use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class Parser
{
    /**
     * 用来解析传输过来的参数，传过来的参数需要有哪些东西？
     */
    private static function requeredParamsCheck(array $requiredParams, $params, Response $response)
    {   
        if (!count($params)) {
            $response->write("neede param.");
            $response->end();
            return false;
        }
        foreach ($requiredParams as $requiredParam) {
            if (!isset($params[$requiredParam]) || $params[$requiredParam] ==='') {
                $response->withStatus(400);
                $response->write("neede param `$requiredParam` not exist.");
                $response->end();
                return false;
            }
        }
        return true;
    }

    static function json(array $requiredParams = [])
    {
        return function (Request $request, Response $response) use ($requiredParams) {
            $params = json_decode($request->getBody()->__toString(), true) ?: [];
            self::requeredParamsCheck($requiredParams, $params, $response);
            return $params;
        };
    }

    static function queryString(array $requiredParams = [])
    {
        return function (Request $request, Response $response) use ($requiredParams) {
            $params = $request->getQueryParams();
            self::requeredParamsCheck($requiredParams, $params, $response);
            return $params;
        };
    }
}
