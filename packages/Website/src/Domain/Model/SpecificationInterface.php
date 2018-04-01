<?php
declare(strict_types=1);

namespace Connections\Website\Domain\Model;

interface SpecificationInterface
{
    public function toSql(): string;
}
