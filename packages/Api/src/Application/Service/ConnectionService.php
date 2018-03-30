<?php
declare(strict_types=1);

namespace Vonq\Api\Application\Service;

use Vonq\Api\Application\Exception\CantAcceptRelationshipException;
use Vonq\Api\Application\Exception\ConnectionAlreadyExistsException;
use Vonq\Api\Domain\Model\ConnectionList;
use Vonq\Api\Domain\Model\ConnectionRepositoryInterface;
use Vonq\Api\Domain\Model\RelationshipConnection;
use Vonq\Api\Domain\Model\RequestedConnection;
use Vonq\Api\Domain\Model\UserId;
use Vonq\Api\Infrastructure\Persistence\Specification\AllRelationshipsForUserSqliteSpecification;

class ConnectionService
{
    private $repository;
    
    public function __construct(ConnectionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function retrieveConnectionsForUser(UserId $userId): ConnectionList
    {
        return $this->repository->query(new AllRelationshipsForUserSqliteSpecification($userId));
    }

    public function inviteUserToConnect(UserId $invitee, UserId $invited)
    {
        $request = new RequestedConnection($invitee, $invited);

        if ($this->repository->exists($request)) {
            throw new ConnectionAlreadyExistsException(
                'Trying to create an already existing connection'
            );
        }

        $this->repository->save($request);
    }

    public function acceptUserInvitation(UserId $invitee, UserId $invited)
    {
        $request = new RequestedConnection($invitee, $invited);
        $relationship = new RelationshipConnection($invitee, $invited);

        if ($this->repository->exists($request) === false) {
            throw new CantAcceptRelationshipException(
                "Can't accept a Relationship that hasn't been requested"
            );
        }

        if ($this->repository->exists($relationship)) {
            throw new CantAcceptRelationshipException(
                "This Relationship Request has already been confirmed"
            );
        }

        $this->repository->save(new RelationshipConnection($invitee, $invited));
    }
}
