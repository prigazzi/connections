<?php
declare(strict_types=1);

namespace Connections\Api\Infrastructure\Persistence;

use Connections\Api\Domain\Model\UserId;

class ConnectionDataMapper
{
    public function fromRecord($record)
    {
        $userFrom = UserId::fromString($record['connection_user_from']);
        $userTo = UserId::fromString($record['connection_user_to']);
        $className = ConnectionTypeMapper::mapType($record['connection_type']);

        return new $className($userFrom, $userTo);
    }
}
