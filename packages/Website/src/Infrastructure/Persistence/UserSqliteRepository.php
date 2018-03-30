<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\Persistence;

use RuntimeException;
use SQLite3;
use Vonq\Website\Domain\Model\UserRepositoryInterface;

class UserSqliteRepository implements UserRepositoryInterface
{
    private $database;

    public function __construct(string $databaseFile)
    {
        $this->database = new Sqlite3($databaseFile);
        $this->createSchema();
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
