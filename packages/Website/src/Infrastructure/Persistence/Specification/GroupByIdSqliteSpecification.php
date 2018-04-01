<?php
declare(strict_types=1);

namespace Connections\Website\Infrastructure\Persistence\Specification;

use SQLite3;
use Connections\Website\Domain\Model\GroupId;
use Connections\Website\Domain\Model\SpecificationInterface;

class GroupByIdSqliteSpecification implements SpecificationInterface
{
    /** @var GroupId */
    private $groupId;

    public function __construct(GroupId $groupId)
    {
        $this->groupId = $groupId;
    }

    public function toSql(): string
    {
        $group_id = Sqlite3::escapeString($this->groupId->toString());

        $query = "
            SELECT
                group_group_id,
                group_name,
                group_description,
                group_created_on
            FROM
                VONQ_GROUPS
            WHERE
                group_group_id = '{$group_id}';
        ";

        return $query;
    }
}
