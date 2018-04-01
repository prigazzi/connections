<?php
declare(strict_types=1);

namespace Connections\Api\Infrastructure\Persistence;

use Connections\Api\Domain\Model\ConnectionInterface;
use Connections\Api\Domain\Model\DeclinedConnection;
use Connections\Api\Domain\Model\RelationshipConnection;
use Connections\Api\Domain\Model\RequestedConnection;

class ConnectionTypeMapper
{
    const SUPPORTED_TYPES = [
        DeclinedConnection::class => 'declined',
        RelationshipConnection::class => 'relationship',
        RequestedConnection::class => 'requested'
    ];

    public static function mapClassName(string $connectionClass): string
    {
        return self::SUPPORTED_TYPES[$connectionClass] ?? self::SUPPORTED_TYPES[RequestedConnection::class];
    }

    public static function mapInstance(ConnectionInterface $connection): string
    {
        return self::mapClassName(get_class($connection));
    }

    public static function mapType(string $type): string
    {
        $supportedTypes = array_flip(self::SUPPORTED_TYPES);

        return $supportedTypes[$type] ?? $supportedTypes['requested'];
    }
}
