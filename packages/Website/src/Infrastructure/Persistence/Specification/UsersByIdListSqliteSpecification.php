<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\Persistence\Specification;

use Vonq\Website\Domain\Model\SpecificationInterface;
use Vonq\Website\Domain\Model\UserId;

class UsersByIdListSqliteSpecification implements SpecificationInterface
{
    private $userIds = [];

    public function __construct(UserId ...$userIds)
    {
        $this->userIds = $userIds;
    }

    public function toSql(): string
    {
        $id_list = implode(", ", array_map(function (UserId $id) {
            return "'" . $id->toString() . "'";
        }, $this->userIds));

        $query = "
            SELECT
                user_user_id,
                user_group_id,
                user_name,
                user_created_on
            FROM
                VONQ_USERS
            WHERE
                user_user_id IN ({$id_list});
        ";

        return $query;
    }
}
