<?php
declare(strict_types=1);

namespace Vonq\Api\Application\Service;

use Vonq\Api\Domain\Model\ConnectionList;
use Vonq\Api\Domain\Model\ConnectionRepositoryInterface;
use Vonq\Api\Domain\Model\RequestedConnection;
use Vonq\Api\Domain\Model\UserId;
use Vonq\Api\Infrastructure\Persistence\Specification\AllConnectionsForUserSqliteSpecification;

class ConnectionService
{
    private $repository;
    
    public function __construct(ConnectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function retrieveConnectionsForUser(UserId $userId): ConnectionList
    {
        return $this->repository->query(new AllConnectionsForUserSqliteSpecification($userId));
    }

    public function inviteUserToConnect(UserId $invitee, UserId $invited)
    {
        $this->repository->save(new RequestedConnection($invitee, $invited));
    }

    public function acceptUserInvitation(UserId $invited, UserId $invitee)
    {
        $this->repository->save(new RequestedConnection($invitee, $invited));
    }
}
