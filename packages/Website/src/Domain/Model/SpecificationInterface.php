<?php
declare(strict_types=1);

namespace Vonq\Website\Domain\Model;

interface SpecificationInterface
{
    public function toSql(): string;
}
