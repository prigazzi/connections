<?php
declare(strict_types=1);

namespace Vonq\Api\Infrastructure\Persistence\Specification;

use SQLite3;
use Vonq\Api\Domain\Model\ConnectionSpecificationInterface;
use Vonq\Api\Domain\Model\UserId;

class AllConnectionsForUserSqliteSpecification implements ConnectionSpecificationInterface
{
    private $id;

    public function __construct(UserId $userId)
    {
        $this->id = $userId;
    }

    public function toSql(): string
    {
        $user_id = Sqlite3::escapeString($this->id->toString());

        $query = "
            SELECT
                connection_user_from,
                connection_user_to,
                connection_type,
                connection_created_on
            FROM
                VONQ_CONNECTIONS
            WHERE
                connection_user_from = '{$user_id}'
        ";

        return $query;
    }
}