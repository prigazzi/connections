<?php
declare(strict_types=1);

namespace Vonq\Api\Application\Service;

use Vonq\Api\Domain\Model\ConnectionRepositoryInterface;
use Vonq\Api\Domain\Model\ConnectionSpecificationInterface;
use Vonq\Api\Domain\Model\RequestedConnection;

class ConnectionService
{
    private $repository;
    
    public function __construct(ConnectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function retrieveConnectionsForUser(
        UserId $userId,
        ConnectionSpecificationInterface $specification = null
    ): ConnectionList {
    }

    public function inviteUserToConnect(UserId $invitee, UserId $invited)
    {
        $this->repository->save(new RequestedConnection($invitee, $invited));
    }

    public function acceptUserInvitation(UserId $invited, UserId $invitee)
    {
    }
}
