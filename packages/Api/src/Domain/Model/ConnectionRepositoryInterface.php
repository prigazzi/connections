<?php
declare(strict_types=1);

namespace Connections\Api\Domain\Model;

interface ConnectionRepositoryInterface
{
    public function query(ConnectionSpecificationInterface $criteria);

    public function save(ConnectionInterface $connection);

    public function exists(ConnectionInterface $connection);
}
