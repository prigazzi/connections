<?php
declare(strict_types=1);

namespace Vonq\Api\Tests\Unit\Domain\Model;

use Vonq\Api\Domain\Model\UserId;
use PHPUnit\Framework\TestCase;

class UserIdTest extends TestCase
{
    public function testGivenTwoDifferentUserIdWhenComparingThemThenTheyAreDifferent()
    {
        $userId1 = new UserId();
        $userId2 = new UserId();

        $this->assertFalse($userId1->equals($userId2));
    }

    public function testGivenANewUserIdAndTheReconstituedOneWhenComparingBothThenTheyAreEqual()
    {
        $userId = new UserId();
        $userIdString = $userId->toString();
        $userIdReconstitued = UserId::fromString($userIdString);

        $this->assertTrue($userId->equals($userIdReconstitued));
        $this->assertNotSame($userId, $userIdReconstitued);
    }
}
