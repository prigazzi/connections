<?php
declare(strict_types=1);

namespace Vonq\Website\Domain\Model;

interface UserRepositoryInterface
{
    public function query(SpecificationInterface $specification): UserList;

    public function exists(UserId $userId): bool;

    public function save(User $user);
}
