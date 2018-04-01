<?php
declare(strict_types=1);

namespace Connections\Api\Domain\Model;

use \InvalidArgumentException;

trait Connection
{
    private $from;
    private $to;

    public function __construct(UserId $from, UserId $to)
    {
        if ($from->equals($to)) {
            throw new InvalidArgumentException(
                "We can't create a Connection between the same user {$from->toString()}"
            );
        }
        
        $this->from = $from;
        $this->to = $to;
    }

    public function fromUserId(): UserId
    {
        return $this->from;
    }

    public function toUserId(): UserId
    {
        return $this->to;
    }

    public function jsonSerialize()
    {
        return [
            'userFrom' => $this->fromUserId()->toString(),
            'userTo' => $this->toUserId()->toString()
        ];
    }
}
