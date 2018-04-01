<?php
declare(strict_types=1);

namespace Connections\Website\Domain\Model;

class GroupId
{
    use IdentityTrait;

    public function equals(GroupId $anotherId)
    {
        return $this->id->equals($anotherId->id);
    }
}
