<?php
declare(strict_types=1);

namespace Connections\Api\Domain\Model;

interface ConnectionSpecificationInterface
{
    public function toSql(): string;
}
