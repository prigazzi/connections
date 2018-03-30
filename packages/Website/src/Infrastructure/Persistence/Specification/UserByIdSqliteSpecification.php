<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\Persistence\Specification;

use SQLite3;
use Vonq\Website\Domain\Model\SpecificationInterface;
use Vonq\Website\Domain\Model\UserId;

class UserByIdSqliteSpecification implements SpecificationInterface
{
    /** @var UserId */
    private $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function toSql(): string
    {
        $user_id = Sqlite3::escapeString($this->userId->toString());

        $query = "
            SELECT
                user_user_id,
                user_group_id,
                user_name,
                user_created_on
            FROM
                VONQ_USERS
            WHERE
                user_user_id = '{$user_id}'
        ";

        return $query;
    }
}
