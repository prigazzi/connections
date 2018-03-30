<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Vonq\Website\Application\Service\UserGroupService;
use Vonq\Website\Domain\Model\GroupRepositoryInterface;
use Vonq\Website\Domain\Model\UserRepositoryInterface;
use Vonq\Website\Infrastructure\Persistence\GroupSqliteRepository;
use Vonq\Website\Infrastructure\Persistence\UserSqliteRepository;
use Vonq\Website\Infrastructure\Presentation\TemplateEngine;
use Vonq\Website\Infrastructure\Presentation\TemplateEngineInterface;
use Vonq\Website\Infrastructure\WebController\DisplayUsersInGroupController;

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
        UserGroupService::class =>
            create(UserGroupService::class)
            ->constructor(
                get(GroupRepositoryInterface::class),
                get(UserRepositoryInterface::class)
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
        'databaseFile' => __DIR__.'/../data/usergroups.sqlite',
        'templateDirectory' => __DIR__.'/../templates/'
    ]
);

return $containerBuilder->build();
