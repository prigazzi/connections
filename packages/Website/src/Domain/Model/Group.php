<?php
declare(strict_types=1);

namespace Connections\Website\Domain\Model;

class Group
{
    /** @var GroupId */
    private $groupId;

    /** @var string */
    private $name;

    /** @var string */
    private $description;

    public function __construct(GroupId $groupId, string $name, string $description)
    {
        $this->groupId = $groupId;
        $this->name = $name;
        $this->description = $description;
    }

    public function getGroupId(): GroupId
    {
        return $this->groupId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
