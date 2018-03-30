<?php
declare(strict_types=1);

namespace Vonq\Website\Domain\Model;

interface UserRepositoryInterface
{
    public function query(SpecificationInterface $specification);

    public function exists(UserId $userId);
}
