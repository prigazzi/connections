<?php
declare(strict_types=1);

namespace Vonq\Website\Tests\Application\Service;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Vonq\Website\Application\Service\UserGroupService;
use Vonq\Website\Domain\Model\GroupId;
use Vonq\Website\Domain\Model\GroupList;
use Vonq\Website\Domain\Model\GroupRepositoryInterface;
use Vonq\Website\Domain\Model\UserId;
use Vonq\Website\Domain\Model\UserList;
use Vonq\Website\Domain\Model\UserRepositoryInterface;
use Vonq\Website\Infrastructure\Api\ConnectionApiClientInterface;

class UserGroupServiceTest extends TestCase
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var GroupRepositoryInterface */
    private $groupRepository;

    /** @var UserGroupService */
    private $service;

    /** @var ConnectionApiClientInterface */
    private $apiClient;

    public function setUp()
    {
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->groupRepository = $this->prophesize(GroupRepositoryInterface::class);
        $this->apiClient = $this->prophesize(ConnectionApiClientInterface::class);

        $this->service = new UserGroupService(
            $this->groupRepository->reveal(),
            $this->userRepository->reveal(),
            $this->apiClient->reveal()
        );
    }

    public function testGivenThereIsNotGroupWhenRequestingGroupInfoThenWeGetAnException()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->groupRepository
            ->query(Argument::any())
            ->willReturn(new GroupList());

        $this->service->userListInformationForGroup(new GroupId());
    }

    public function testGivenUserDoesntExistWhenRetrievingContactsTheWeGetAnException()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->userRepository
            ->query(Argument::any())
            ->willReturn(new UserList());

        $this->service->contactsForUser(new UserId());
    }
}
