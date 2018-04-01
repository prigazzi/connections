<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use GuzzleHttp\Client;
use Connections\Website\Application\Service\UserGroupService;
use Connections\Website\Domain\Model\GroupRepositoryInterface;
use Connections\Website\Domain\Model\UserRepositoryInterface;
use Connections\Website\Infrastructure\Api\ConnectionApiClientInterface;
use Connections\Website\Infrastructure\Api\ConnectionGuzzleApiClient;
use Connections\Website\Infrastructure\Persistence\GroupSqliteRepository;
use Connections\Website\Infrastructure\Persistence\UserSqliteRepository;
use Connections\Website\Infrastructure\Presentation\TemplateEngine;
use Connections\Website\Infrastructure\Presentation\TemplateEngineInterface;
use Connections\Website\Infrastructure\WebController\DisplayUserContactsController;
use Connections\Website\Infrastructure\WebController\DisplayUsersInGroupController;

use function DI\create;
use function DI\get;
use function DI\factory;

require_once __DIR__.'/../../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions(
    [
        DisplayUsersInGroupController::class =>
        create(DisplayUsersInGroupController::class)
            ->constructor(
                get(UserGroupService::class),
                get(TemplateEngineInterface::class)
            ),
        DisplayUserContactsController::class =>
        create(DisplayUserContactsController::class)
            ->constructor(
                get(UserGroupService::class),
                get(TemplateEngineInterface::class)
            ),
        UserGroupService::class =>
        create(UserGroupService::class)
            ->constructor(
                get(GroupRepositoryInterface::class),
                get(UserRepositoryInterface::class),
                get(ConnectionApiClientInterface::class)
            ),
        GroupRepositoryInterface::class =>
        create(GroupSqliteRepository::class)
            ->constructor(get('databaseFile')),
        UserRepositoryInterface::class =>
        create(UserSqliteRepository::class)
            ->constructor(get('databaseFile')),
        TemplateEngineInterface::class =>
        factory(
            function (string $templateDirectory) {
                $loader = new Twig_Loader_Filesystem($templateDirectory);
                $twig = new Twig_Environment($loader);

                return new TemplateEngine($twig);
            }
        )->parameter('templateDirectory', get('templateDirectory')),
        ConnectionApiClientInterface::class =>
        create(ConnectionGuzzleApiClient::class)
            ->constructor(
                create(Client::class)
            ),
        'databaseFile' => __DIR__.'/../data/usergroups.sqlite',
        'templateDirectory' => __DIR__.'/../templates/'
    ]
);

return $containerBuilder->build();
