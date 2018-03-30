<?php
declare(strict_types=1);

namespace Vonq\Api\Domain\Model;

class ConnectionList extends GenericList
{
    public function __construct(ConnectionInterface ...$connections)
    {
        $this->values = $connections;
    }
}

