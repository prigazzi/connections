<?php
declare(strict_types=1);

namespace Vonq\Api\Application\Service;

use Vonq\Api\Domain\Model\ConnectionRepositoryInterface;

class ConnectionService
{
    private $repository;
    
    public function __construct(ConnectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function retrieveConnectionsForUser(
        UserId $userId,
        ConnectionSelectionCriteria $criteria = null
    ): ConnectionList {
        
    }

    public function inviteUserToConnect(UserId $invitee, UserId $invited)
    {
        
    }
}
