<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\Persistence;

use RuntimeException;
use SQLite3;
use Vonq\Website\Domain\Model\Group;
use Vonq\Website\Domain\Model\GroupId;
use Vonq\Website\Domain\Model\GroupList;
use Vonq\Website\Domain\Model\GroupRepositoryInterface;
use Vonq\Website\Domain\Model\SpecificationInterface;

class GroupSqliteRepository implements GroupRepositoryInterface
{
    private $database;

    public function __construct(string $databaseFile)
    {
        $this->database = new Sqlite3($databaseFile);
        $this->createSchema();
    }

    public function query(SpecificationInterface $specification): GroupList
    {
        $groupList = [];
        $result = $this->database->query($specification->toSql());

        while ($record = $result->fetchArray(SQLITE3_ASSOC)) {
            $groupList[] = new Group(
                GroupId::fromString($record['group_group_id']),
                $record['group_name'],
                $record['group_description']
            );
        }

        return new GroupList(...$groupList);
    }

    public function exists(GroupId $groupId)
    {

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
