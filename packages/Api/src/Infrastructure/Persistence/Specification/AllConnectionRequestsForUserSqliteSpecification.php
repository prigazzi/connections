<?php
declare(strict_types=1);

namespace Connections\Api\Infrastructure\Persistence\Specification;

use Connections\Api\Domain\Model\ConnectionSpecificationInterface;
use Connections\Api\Domain\Model\RequestedConnection;
use Connections\Api\Domain\Model\UserId;
use Connections\Api\Infrastructure\Persistence\ConnectionTypeMapper;

class AllConnectionRequestsForUserSqliteSpecification implements ConnectionSpecificationInterface
{
    private $id;

    public function __construct(UserId $userId)
    {
        $this->id = $userId;
    }

    public function toSql(): string
    {
        $type = ConnectionTypeMapper::mapClassName(RequestedConnection::class);

        $query = (new AllConnectionsForUserSqliteSpecification($this->id))->toSql();
        $query.= " AND connection_type = '{$type}'";

        return $query;
    }
}
