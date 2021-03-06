<?php
declare(strict_types=1);

namespace Connections\Website\Domain\Model;

interface GroupRepositoryInterface
{
    public function query(SpecificationInterface $specification): GroupList;

    public function exists(GroupId $groupId): bool;
}
