<?php
declare(strict_types=1);

namespace Connections\Website\Domain\Model;

class GroupList extends GenericList
{
    public function __construct(Group ...$groups)
    {
        $this->values = $groups;
    }
}
