<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {
        $this->setGlobalMode(true);
        // fake data
        $routeCollector->addGroup('/v1', function (RouteCollector $route) {

            // 查询路由
            $route->addGroup('/q', function (RouteCollector $api) {
                $api->get('/balance', '/api/QueryController/balance');
                $api->get('/orders', '/api/QueryController/orders');
                $api->get('/order', '/api/QueryController/order');
            });

            // 下单路由
            $route->addGroup('/order', function (RouteCollector $api) {
                $api->post('/create/{type: (?:market|limit)}/{side: (?:buy|sell)}', '/api/OrderController/add');
                $api->post('/cancel', '/api/OrderController/cancel');
            });
        });

        // 方法不允许的时候报的错
        $this->setMethodNotAllowCallBack(function (Request $request, Response $response) {
            $response->withStatus(405);
            $method = $request->getMethod();
            $page = <<<EOF
            <h2>$method Method Not Allow</h2>
EOF;
            $response->write($page);
        });

        // 当没有该路由的时候显示
        $this->setRouterNotFoundCallBack(function (Request $request, Response $response) {
            $response->withStatus(404);
            $notFoundPath = $request->getUri()->getPath();
            $page = <<<EOF
            <h2>$notFoundPath Page Not Found</h2>
EOF;
            $response->write($page);
        });
    }
}
