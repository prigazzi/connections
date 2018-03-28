<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Vonq\Api\Infrastructure\WebController\CreateConnectionRequestController;
use Vonq\Api\Infrastructure\WebController\UserConnectionListController;
use Zend\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;

use function DI\create;
use function DI\get;

require_once __DIR__.'/../../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions(
    [
        CreateConnectionRequestController::class => create(CreateConnectionRequestController::class)
            ->constructor(get(ResponseInterface::class)),
        UserConnectionListController::class => create(UserConnectionListController::class)
            ->constructor(get(ResponseInterface::class)),
        ResponseInterface::class => function() {
            return new Response();
        }
    ]
);

return $containerBuilder->build();
