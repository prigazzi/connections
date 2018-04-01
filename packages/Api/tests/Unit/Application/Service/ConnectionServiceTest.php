<?php
declare(strict_types=1);

namespace Connections\Api\Tests\Application\Service;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Connections\Api\Application\Exception\CantAcceptRelationshipException;
use Connections\Api\Application\Exception\ConnectionAlreadyExistsException;
use Connections\Api\Application\Service\ConnectionService;
use Connections\Api\Domain\Model\ConnectionRepositoryInterface;
use Connections\Api\Domain\Model\RelationshipConnection;
use Connections\Api\Domain\Model\RequestedConnection;
use Connections\Api\Domain\Model\UserId;

class ConnectionServiceTest extends TestCase
{
    /** @var ConnectionService */
    private $service;

    /** @var ConnectionRepositoryInterface */
    private $repository;

    public function setUp()
    {
        $this->repository = $this->prophesize(ConnectionRepositoryInterface::class);
        $this->service = new ConnectionService($this->repository->reveal());
    }

    public function testGivenAConnectionRequestAlreadyExistsWhenInvitingToConnectThenWeGetAnException()
    {
        $this->expectException(ConnectionAlreadyExistsException::class);

        $this->repository
            ->exists(Argument::type(RequestedConnection::class))
            ->willReturn(true);

        $this->service->inviteUserToConnect(new UserId(), new UserId());
    }

    public function testGivenThereIsntAConnectionRequestWhenAcceptingAConnectionRequestThenWeGetAnException()
    {
        $this->expectException(CantAcceptRelationshipException::class);

        $this->repository
            ->exists(Argument::type(RequestedConnection::class))
            ->willReturn(false);

        $this->service->acceptUserInvitation(new UserId(), new UserId());
    }

    public function testGivenThereIsARelastionshipAcceptedWhenAcceptingAConnectionRequestThenWeGetAnException()
    {
        $this->expectException(CantAcceptRelationshipException::class);

        $this->repository
            ->exists(Argument::type(RequestedConnection::class))
            ->willReturn(true);

        $this->repository
            ->exists(Argument::type(RelationshipConnection::class))
            ->willReturn(true);

        $this->service->acceptUserInvitation(new UserId(), new UserId());
    }

    public function testGivenThereIsARequestedConnectionAndNotARelationshipAcceptedWhenAcceptionRelationshipThenWorks()
    {
        $this->repository
            ->exists(Argument::type(RequestedConnection::class))
            ->willReturn(true);

        $this->repository
            ->exists(Argument::type(RelationshipConnection::class))
            ->willReturn(false);

        $this->repository
            ->save(Argument::type(RelationshipConnection::class))
            ->shouldBeCalledTimes(2);

        $this->service->acceptUserInvitation(new UserId(), new UserId());
    }
}
