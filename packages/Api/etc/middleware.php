<?php
declare(strict_types=1);

use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Zend\Diactoros\Response;
use Vonq\Api\Infrastructure\WebController\TestController;

use function FastRoute\simpleDispatcher;

$routes = simpleDispatcher(function (RouteCollector $r) {
    $r->get('/hello', TestController::class);
});

$middlewares = [];
$middlewares[] = new FastRoute($routes);
$middlewares[] = new RequestHandler($container);

return $middlewares;
