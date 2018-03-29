<?php
declare(strict_types=1);

namespace Vonq\Api\Domain\Model;

interface ConnectionRepositoryInterface
{
    public function retrieveConnectionsForUser(
        UserId $userId,
        ConnectionSelectionCriteria $criteria = null
    ): ConnectionList;

    public function inviteUserToConnect(UserId $invitee, UserId $invited);
}