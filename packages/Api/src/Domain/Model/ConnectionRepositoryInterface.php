<?php
declare(strict_types=1);

namespace Vonq\Api\Domain\Model;

interface ConnectionRepositoryInterface
{
    public function forUserIdAndCriteria(UserId $userId, ConnectionSelectionCriteria $criteria);

    public function save(ConnectionInterface $connection);
}
