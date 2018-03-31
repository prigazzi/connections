<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\Persistence;

use RuntimeException;
use SQLite3;
use Vonq\Website\Domain\Model\GroupId;
use Vonq\Website\Domain\Model\SpecificationInterface;
use Vonq\Website\Domain\Model\User;
use Vonq\Website\Domain\Model\UserId;
use Vonq\Website\Domain\Model\UserList;
use Vonq\Website\Domain\Model\UserRepositoryInterface;
use Vonq\Website\Infrastructure\Persistence\Specification\UserByIdSqliteSpecification;

class UserSqliteRepository implements UserRepositoryInterface
{
    private $database;

    public function __construct(string $databaseFile)
    {
        $this->database = new Sqlite3($databaseFile);
        $this->createSchema();
    }

    public function query(SpecificationInterface $specification): UserList
    {
        $userList = [];
        $result = $this->database->query($specification->toSql());

        while ($record = $result->fetchArray(SQLITE3_ASSOC)) {
            $userList[] = new User(
                UserId::fromString($record['user_user_id']),
                GroupId::fromString($record['user_group_id']),
                $record['user_name']
            );
        }

        return new UserList(...$userList);
    }

    public function exists(UserId $userId): bool
    {
        return count($this->query(new UserByIdSqliteSpecification($userId))) === 1;
    }

    public function save(User $user)
    {
        $user_id = Sqlite3::escapeString($user->getUserId()->toString());
        $group_id = Sqlite3::escapeString($user->getGroupId()->toString());
        $name = Sqlite3::escapeString($user->getName());

        $query = "
        INSERT INTO VONQ_USERS
        (
            user_user_id,
            user_group_id,
            user_name,
            user_created_on
        )
        VALUES
        (
            '{$user_id}',
            '{$group_id}',
            '{$name}',
            CURRENT_TIMESTAMP
        )
        ";

        $this->database->exec($query);
    }

    private function createSchema()
    {
        $isValid = $this->database->exec("
        CREATE TABLE IF NOT EXISTS VONQ_USERS (
            user_user_id CHAR(36) PRIMARY KEY NOT NULL,
            user_group_id CHAR(36) NOT NULL,
            user_name VARCHAR(255) NOT NULL,
            user_created_on DATETIME NOT NULL
        )");

        if (!$isValid) {
            throw new RuntimeException("Couldn't create User Table");
        }
    }
}
