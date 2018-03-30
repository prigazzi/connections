<?php
declare(strict_types=1);

namespace Vonq\Website\Infrastructure\Api;

use Vonq\Website\Domain\Model\UserId;

interface ConnectionApiClientInterface
{
    public function retrieveRelationshipsForUser(UserId $userId);
}
