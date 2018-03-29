<?php
declare(strict_types=1);

namespace Vonq\Api\Domain\Model;

use Ramsey\Uuid\Uuid;

class UserId
{
    private $id;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public static function fromString(string $uuid)
    {
        if (Uuid::isValid($uuid) === false) {
            throw new InvalidArgumentException("Uuid {$uuid} is invalid");
        }

        $userId = new self();
        $userId->id = Uuid::fromString($uuid);

        return $userId;
    }

    public function toString()
    {
        return $this->id->toString();
    }
    
    public function equals(UserId $anotherId)
    {
        return $this->id->equals($anotherId->id);
    }
}
