<?php

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\AbstractRouter;
use FastRoute\RouteCollector;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class Router extends AbstractRouter {
    function initialize(RouteCollector $routeCollector)
    {
        $this->setGlobalMode(true);
        $routeCollector->get('/', function (Request $request, Response $response) {
            $response->withStatus(403);
        });

        $routeCollector->addGroup('/api/v1', function(RouteCollector $api) {
            $api->get('/query', '/api/QueryController');
            $api->post('/query/balance', '/api/QueryController/balance');
            $api->post('/query/ticker', '/api/QueryController/ticker');
            $api->post('/query/order', '/api/QueryController/order');
            /**
             * 一堆坑：
             *      version:
             *      password: 
             */
            $api->get('/', '/Index/index');
            $api->get('/query/balance', '/api/QueryController/balance');
            $api->get('/query/ticker', '/api/QueryController/ticker');
            $api->get('/query/order', '/api/QueryController/order');
        });
    }
}