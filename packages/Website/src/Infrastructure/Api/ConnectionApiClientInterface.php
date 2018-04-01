<?php
declare(strict_types=1);

namespace Connections\Website\Infrastructure\Api;

use Connections\Website\Domain\Model\UserId;

interface ConnectionApiClientInterface
{
    public function retrieveRelationshipsForUser(UserId $userId);
}
