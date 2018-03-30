<?php
declare(strict_types=1);

namespace Vonq\Api\Domain\Model;

interface ConnectionSpecificationInterface
{
    public function toSql(): string;
}
