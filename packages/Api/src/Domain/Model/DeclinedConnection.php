<?php
declare(strict_types=1);

namespace Vonq\Api\Domain\Model;

use JsonSerializable;

class DeclinedConnection implements ConnectionInterface, JsonSerializable
{
    use Connection;
}
