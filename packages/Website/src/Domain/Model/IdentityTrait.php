<?php
declare(strict_types=1);

namespace Connections\Website\Domain\Model;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

trait IdentityTrait
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

        $identity = new self();
        $identity->id = Uuid::fromString($uuid);

        return $identity;
    }

    public function toString()
    {
        return $this->id->toString();
    }
}