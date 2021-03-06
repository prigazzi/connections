<?php
declare(strict_types=1);

namespace Connections\Api\Domain\Model;

interface ConnectionInterface
{
    public function fromUserId(): UserId;

    public function toUserId(): UserId;
}
