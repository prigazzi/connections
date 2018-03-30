<?php
declare(strict_types=1);

namespace Vonq\Api\Domain\Model;

use JsonSerializable;

class RelationshipConnection implements ConnectionInterface, JsonSerializable
{
    use Connection;
}
