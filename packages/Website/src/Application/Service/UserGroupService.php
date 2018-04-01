<?php
declare(strict_types=1);

namespace Connections\Website\Application\Service;

use InvalidArgumentException;
use Connections\Website\Domain\Model\Group;
use Connections\Website\Domain\Model\GroupId;
use Connections\Website\Domain\Model\GroupRepositoryInterface;
use Connections\Website\Domain\Model\User;
use Connections\Website\Domain\Model\UserId;
use Connections\Website\Domain\Model\UserRepositoryInterface;
use Connections\Website\Infrastructure\Api\ConnectionApiClientInterface;
use Connections\Website\Infrastructure\Persistence\GroupSqliteRepository;
use Connections\Website\Infrastructure\Persistence\Specification\GroupByIdSqliteSpecification;
use Connections\Website\Infrastructure\Persistence\Specification\UserByIdSqliteSpecification;
use Connections\Website\Infrastructure\Persistence\Specification\UsersByIdListSqliteSpecification;
use Connections\Website\Infrastructure\Persistence\Specification\UsersForGroupSqliteSpecification;
use Connections\Website\Infrastructure\Persistence\UserSqliteRepository;

class UserGroupService
{
    const DEFAULT_GROUP_ID = '9be55c55-d238-4ebf-885f-ceafb2cb8c72';

    /** @var GroupRepositoryInterface */
    private $groupRepository;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var ConnectionApiClientInterface */
    private $apiClient;

    public function __construct(
        GroupRepositoryInterface $groupRepository,
        UserRepositoryInterface $userRepository,
        ConnectionApiClientInterface $apiClient
    ) {
        $this->groupRepository = $groupRepository;
        $this->userRepository = $userRepository;
        $this->apiClient = $apiClient;
    }

    public function userListInformationForGroup(GroupId $groupId): array
    {
        $groupList = $this->groupRepository->query(new GroupByIdSqliteSpecification($groupId));

        if (count($groupList) !== 1) {
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
