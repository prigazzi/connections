<?php
declare(strict_types=1);

namespace Connections\Api\Domain\Model;

use JsonSerializable;

class DeclinedConnection implements ConnectionInterface, JsonSerializable
{
    use Connection;
}
