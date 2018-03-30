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

    public function exists(UserId $userId)
    {

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
