<?php
declare(strict_types=1);

namespace Connections\Api\Tests\Unit\Domain\Model;

use Connections\Api\Domain\Model\UserId;
use Connections\Api\Domain\Model\ConnectionInterface;
use Connections\Api\Domain\Model\Connection;
use PHPUnit\Framework\TestCase;
use \InvalidArgumentException;

class ConnectionTest extends TestCase
{
    private $connection;

    public function testGivenTwoDifferentUsersWhenCreatingAConnectionThenTheObjectIsBuilt()
    {
        $userId1 = new UserId();
        $userId2 = new UserId();
        $connection = new class ($userId1, $userId2) implements ConnectionInterface
        {
            use Connection;
        };

        $this->assertTrue($connection->fromUserId()->equals($userId1));
        $this->assertTrue($connection->toUserId()->equals($userId2));
        $this->assertFalse($connection->fromUserId()->equals($connection->toUserId()));
    }

    public function testGivenTheSameUserIdWhenCreatingAConnectionThenExceptionIsThrown()
    {
        $this->expectException(InvalidArgumentException::class);
        $userId1 = new UserId();
        $connection = new class ($userId1, $userId1) implements ConnectionInterface
        {
            use Connection;
        };
    }
}
