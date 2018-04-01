<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Connections\Api\Application\Service\ConnectionService;
use Connections\Api\Domain\Model\ConnectionRepositoryInterface;
use Connections\Api\Infrastructure\Persistence\ConnectionSqliteRepository;
use Connections\Api\Infrastructure\WebController\AcceptConnectionRequestController;
use Connections\Api\Infrastructure\WebController\CreateConnectionRequestController;
use Connections\Api\Infrastructure\WebController\UserConnectionListController;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\JsonResponse;

use function DI\create;
use function DI\get;

require_once __DIR__.'/../../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions(
    [
        CreateConnectionRequestController::class => 
            create(CreateConnectionRequestController::class)
            ->constructor(
                get(Response::class),
                get(ConnectionService::class)
            ),
        AcceptConnectionRequestController::class =>
            create(AcceptConnectionRequestController::class)
            ->constructor(
                get(Response::class),
                get(ConnectionService::class)
            ),
        UserConnectionListController::class => 
            create(UserConnectionListController::class)
            ->constructor(
                get(JsonResponse::class),
                get(ConnectionService::class)
            ),
        ConnectionService::class => 
            create(ConnectionService::class)
            ->constructor(get(ConnectionRepositoryInterface::class)),
        ConnectionRepositoryInterface::class => 
            create(ConnectionSqliteRepository::class)
            ->constructor(get('databaseFile')),
        JsonResponse::class => function () {
            return new JsonResponse([]);
        },
        Response::class => function () {
            return new Response();
        },
        'databaseFile' => __DIR__.'/../data/connections.sqlite'
    ]
);

return $containerBuilder->build();
