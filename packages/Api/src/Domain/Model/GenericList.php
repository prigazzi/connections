<?php
declare(strict_types=1);

namespace Vonq\Api\Domain\Model;

use ArrayIterator;
use Countable;
use IteratorAggregate;

class GenericList implements IteratorAggregate, Countable
{
    protected $values = [];

    public function getIterator()
    {
        return new ArrayIterator($this->values);
    }

    public function toArray()
    {
        return $this->values;
    }

    public function count()
    {
        return count($this->values);
    }
}
