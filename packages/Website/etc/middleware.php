<?php
declare(strict_types=1);

use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Vonq\Website\Infrastructure\WebController\DisplayUserContactsController;
use Vonq\Website\Infrastructure\WebController\DisplayUsersInGroupController;

use function FastRoute\simpleDispatcher;

$routes = simpleDispatcher(function (RouteCollector $r) {
    $r->get('/', DisplayUsersInGroupController::class);
    $r->get('/user/{userId}', DisplayUserContactsController::class);
});

$middlewares = [];
$middlewares[] = new FastRoute($routes);
$middlewares[] = new RequestHandler($container);

return $middlewares;
