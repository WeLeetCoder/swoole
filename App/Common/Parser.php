<?php

namespace App\Common;

use EasySwoole\Http\Message\Request;
use EasySwoole\Http\Message\Response;

class Parser {
    /**
     * 用来解析传输过来的参数，传过来的参数需要有哪些东西？
     */
    static function json(array $requiredParams) {
        return function (Request $request, Response $response) use ($requiredParams) {
            $params = json_decode($request->getBody()->__toString(), true) ?: [];
            if (!count($params)) {
                $response->write("required params.");
                $response->end();
                return false;
            }
            foreach ($requiredParams as $requiredParam) {
                if (!isset($params[$requiredParam])) {
                    $response->write("需要 `$requiredParam` 参数不存在。");
                    $response->end();
                    return false;
                }
            }
            return $params;
        };
    }

    static function queryString() {
        return function (Request $request, Response $response) {
            $params = $request->getQueryParams();
            if (!count($params)) {
                $response->write("fuck");
                $response->end();
                return false;
            }
        };
    }
}