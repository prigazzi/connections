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
use Vonq\Website\Infrastructure\Persistence\Specification\GroupByIdSqliteSpecification;

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

    public function exists(GroupId $groupId): bool
    {
        return count($this->query(new GroupByIdSqliteSpecification($groupId))) === 1;
    }

    public function save(Group $group)
    {
        $group_id = Sqlite3::escapeString($group->getGroupId()->toString());
        $name = Sqlite3::escapeString($group->getName());
        $description = Sqlite3::escapeString($group->getDescription());

        $query = "
        INSERT INTO VONQ_GROUPS
        (
            group_group_id,
            group_name,
            group_description,
            group_created_on
        )
        VALUES
        (
            '{$group_id}',
            '{$name}',
            '{$description}',
            CURRENT_TIMESTAMP
        )
        ";

        $this->database->exec($query);
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
