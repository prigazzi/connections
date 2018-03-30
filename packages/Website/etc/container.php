<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Vonq\Website\Domain\Model\GroupRepositoryInterface;
use Vonq\Website\Domain\Model\UserRepositoryInterface;
use Vonq\Website\Infrastructure\Persistence\GroupSqliteRepository;
use Vonq\Website\Infrastructure\Persistence\UserSqliteRepository;
use Vonq\Website\Infrastructure\WebController\DisplayUsersInGroupController;

use function DI\create;
use function DI\get;

require_once __DIR__.'/../../../vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$containerBuilder->useAutowiring(false);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions(
    [
        DisplayUsersInGroupController::class =>
            create(DisplayUsersInGroupController::class)
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
        'databaseFile' => __DIR__.'/../data/usergroups.sqlite'
    ]
);

return $containerBuilder->build();
