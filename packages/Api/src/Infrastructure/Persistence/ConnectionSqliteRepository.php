<?php
declare(strict_types=1);

namespace Connections\Api\Infrastructure\Persistence;

use Connections\Api\Domain\Model\ConnectionInterface;
use Connections\Api\Domain\Model\ConnectionList;
use Connections\Api\Domain\Model\ConnectionRepositoryInterface;
use Connections\Api\Domain\Model\ConnectionSpecificationInterface;
use \RuntimeException;
use \Sqlite3;

class ConnectionSqliteRepository implements ConnectionRepositoryInterface
{
    private $database;
    private $mapper;
    
    public function __construct(string $databaseFile)
    {
        $this->database = new Sqlite3($databaseFile);
        $this->mapper = new ConnectionDataMapper();
        $this->createSchema();
    }
    
    public function query(ConnectionSpecificationInterface $specification)
    {
        $connectionList = [];
        $result = $this->database->query($specification->toSql());

        if ($result->numColumns() === 0 && $result->columnType(0) === SQLITE3_NULL) {
            return null;
        }

        while ($record = $result->fetchArray(SQLITE3_ASSOC)) {
            $connectionList[] = $this->mapper->fromRecord($record);
        }

        return new ConnectionList(...$connectionList);
    }

    public function save(ConnectionInterface $connection)
    {
        $user_from = Sqlite3::escapeString($connection->fromUserId()->toString());
        $user_to = Sqlite3::escapeString($connection->toUserId()->toString());
        $type = ConnectionTypeMapper::mapInstance($connection);
        
        $query = "
        INSERT INTO VONQ_CONNECTIONS
        (
            connection_user_from,
            connection_user_to,
            connection_type,
            connection_created_on
        )
        VALUES
        (
            '{$user_from}',
            '{$user_to}',
            '{$type}',
            CURRENT_TIMESTAMP
        )
        ";
        
        $this->database->exec($query);
    }

    public function exists(ConnectionInterface $connection)
    {
        $user_from = Sqlite3::escapeString($connection->fromUserId()->toString());
        $user_to = Sqlite3::escapeString($connection->toUserId()->toString());
        $type = ConnectionTypeMapper::mapInstance($connection);
        
        $result = $this->database->query("
            SELECT
                count(connection_user_from) as amount
            FROM
                VONQ_CONNECTIONS
            WHERE
                connection_user_from = '{$user_from}' AND
                connection_user_to = '{$user_to}' AND
                connection_type = '{$type}'
         ");
    
        if ($result->numColumns() === 0 && $result->columnType(0) === SQLITE3_NULL) {
            return false;
        }

        $amount = $result->fetchArray();
        return $amount['amount'] === 1;
    }

    private function createSchema()
    {
        $isValid = $this->database->exec("
        CREATE TABLE IF NOT EXISTS VONQ_CONNECTIONS (
            connection_user_from CHAR(36) NOT NULL,
            connection_user_to CHAR(36) NOT NULL,
            connection_type VARCHAR(255) NOT NULL,
            connection_created_on DATETIME NOT NULL,
            PRIMARY KEY (connection_user_from, connection_user_to, connection_type)
        )");

        if (!$isValid) {
            throw new RuntimeException("Couldn't create Connection Table");
        }
    }
}
