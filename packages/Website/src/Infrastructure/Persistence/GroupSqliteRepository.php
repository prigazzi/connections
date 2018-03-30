<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\Persistence;

use RuntimeException;
use SQLite3;
use Vonq\Website\Domain\Model\GroupRepositoryInterface;

class GroupSqliteRepository implements GroupRepositoryInterface
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
        CREATE TABLE IF NOT EXISTS VONQ_GROUPS (
            group_group_id CHAR(36) PRIMARY KEY NOT NULL,
            group_name VARCHAR(255) NOT NULL,
            group_description VARCHAR(255) NOT NULL,
            group_created_on DATETIME NOT NULL
        )");

        if (!$isValid) {
            throw new RuntimeException("Couldn't create Group Table");
        }
    }
}
