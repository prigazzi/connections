<?php
declare(strict_types=1);

namespace Connections\Website\Domain\Model;

class UserId
{
    use IdentityTrait;

    public function equals(UserId $anotherId)
    {
        return $this->id->equals($anotherId->id);
    }
}
