<?php
declare(strict_types=1);

use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Zend\Diactoros\Response;

use function FastRoute\simpleDispatcher;

$routes = simpleDispatcher(function (RouteCollector $r) {
    $r->get('/hello', function () {
        return new Response('Hi :)');
    });
});

$middlewares = [];
$middlewares[] = new FastRoute($routes);
$middlewares[] = new RequestHandler();

return $middlewares;
