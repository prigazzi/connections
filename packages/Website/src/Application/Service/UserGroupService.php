<?php
declare(strict_types=1);

namespace Vonq\Website\Application\Service;

use InvalidArgumentException;
use Vonq\Website\Domain\Model\Group;
use Vonq\Website\Domain\Model\GroupId;
use Vonq\Website\Domain\Model\User;
use Vonq\Website\Domain\Model\UserId;
use Vonq\Website\Infrastructure\Api\ConnectionApiClientInterface;
use Vonq\Website\Infrastructure\Persistence\GroupSqliteRepository;
use Vonq\Website\Infrastructure\Persistence\Specification\GroupByIdSqliteSpecification;
use Vonq\Website\Infrastructure\Persistence\Specification\UserByIdSqliteSpecification;
use Vonq\Website\Infrastructure\Persistence\Specification\UsersByIdListSqliteSpecification;
use Vonq\Website\Infrastructure\Persistence\Specification\UsersForGroupSqliteSpecification;
use Vonq\Website\Infrastructure\Persistence\UserSqliteRepository;

class UserGroupService
{
    /** @var GroupSqliteRepository */
    private $groupRepository;

    /** @var UserSqliteRepository */
    private $userRepository;

    /** @var ConnectionApiClientInterface */
    private $apiClient;

    public function __construct(
        GroupSqliteRepository $groupRepository,
        UserSqliteRepository $userRepository,
        ConnectionApiClientInterface $apiClient
    ) {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
        $this->apiClient = $apiClient;
    }

    public function userListInformationForGroup(GroupId $groupId): array
    {
        $groupList = $this->groupRepository->query(new GroupByIdSqliteSpecification($groupId));

        if (count($groupList) === 0) {
            throw new InvalidArgumentException(
                'The requested group doesn\'t exist'
            );
        }

        $usersList = $this->userRepository->query(new UsersForGroupSqliteSpecification($groupId));

        /** @var Group $group */
        $group = $groupList->toArray()[0];
        return [
            'groupName' => $group->getName(),
            'groupDescription' => $group->getDescription(),
            'userList' => array_map(function (User $user) {
                return [
                    'name' => $user->getName(),
                    'userId' => $user->getUserId()->toString()
                ];
            }, $usersList->toArray())
        ];
    }

    public function contactsForUser(UserId $userId)
    {
        $userList = $this->userRepository->query(new UserByIdSqliteSpecification($userId));

        if (count($userList) !== 1) {
            throw new InvalidArgumentException(
                'The requested User doesn\'t exist'
            );
        }

        $connectionIds = $this->apiClient->retrieveRelationshipsForUser($userId);
        $connections = $this->userRepository->query(new UsersByIdListSqliteSpecification(...$connectionIds));

        /** @var User $user */
        $user = $userList->toArray()[0];

        return [
            'userName' => $user->getName(),
            'connections' => array_map(function (User $user) {
                return [
                    'userId' => $user->getUserId()->toString(),
                    'name' => $user->getName()
                ];
            }, $connections->toArray())
        ];
    }
}
