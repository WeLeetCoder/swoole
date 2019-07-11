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
        // fake data
        $routeCollector->addGroup('/v1', function (RouteCollector $api) { 
            $api->get('/query', '/api/FakeQueryController');
            $api->get('/query/balance', '/api/FakeQueryController/balance');
        });

        // dev api
        $routeCollector->addGroup('/v1/dev', function (RouteCollector $api) {
            $api->get('/query/balance', '/api/QueryController/balance');
            $api->get('/query/order', '/api/QueryController/order');
        });

        $this->setGlobalMode(true);
        $this->setRouterNotFoundCallBack(function (Request $request, Response $response) {
            $response->withStatus(404);
            $response->write("page is not found.");
        });
    }
}
