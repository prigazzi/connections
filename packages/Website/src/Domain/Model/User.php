<?php
declare(strict_types=1);

namespace Vonq\Website\Domain\Model;

class User
{
    /** @var UserId */
    private $userId;

    /** @var GroupId */
    private $groupId;

    /** @var string */
    private $name;

    public function __construct(UserId $userId, GroupId $groupId, string $name)
    {
        $this->userId = $userId;
        $this->groupId = $groupId;
        $this->name = $name;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getGroupId(): GroupId
    {
        return $this->groupId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
