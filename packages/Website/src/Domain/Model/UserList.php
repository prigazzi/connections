<?php
declare(strict_types=1);

namespace Connections\Website\Domain\Model;

class UserList extends GenericList
{
    public function __construct(User ...$users)
    {
        $this->values = $users;
    }
}
