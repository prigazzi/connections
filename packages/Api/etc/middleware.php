<?php
declare(strict_types=1);

use FastRoute\RouteCollector;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Connections\Api\Infrastructure\WebController\AcceptConnectionRequestController;
use Connections\Api\Infrastructure\WebController\CreateConnectionRequestController;
use Connections\Api\Infrastructure\WebController\UserConnectionListController;

use function FastRoute\simpleDispatcher;

$routes = simpleDispatcher(function (RouteCollector $r) {
    $r->post('/api/connection', CreateConnectionRequestController::class);
    $r->post('/api/connection/accept', AcceptConnectionRequestController::class);
    $r->get('/api/connection/{userId}', UserConnectionListController::class);
});

$middlewares = [];
$middlewares[] = new FastRoute($routes);
$middlewares[] = new RequestHandler($container);

return $middlewares;
