<?php
declare(strict_types=1);

namespace Vonq\Api\Infrastructure\Persistence;

use Vonq\Api\Domain\Model\ConnectionInterface;
use Vonq\Api\Domain\Model\ConnectionRepositoryInterface;
use Vonq\Api\Domain\Model\ConnectionSelectionCriteria;
use Vonq\Api\Domain\Model\UserId;
use \InvalidArgumentException;
use \RuntimeException;
use \Sqlite3;

class ConnectionSqliteRepository implements ConnectionRepositoryInterface
{
    private $databse;
    private $mapper;
    
    public function __construct(string $databaseFile)
    {
        $this->database = new Sqlite3($databaseFile);
        $this->mapper = new ConnectionDataMapper();
        $this->createSchema();
    }
    
    public function forUserIdAndCriteria(UserId $userId, ConnectionSelectionCriteria $criteria)
    {
    }

    public function save(ConnectionInterface $connection)
    {
        if ($this->exists($connection)) {
            throw new InvalidArgumentException(
                'Trying to create an already existing connection'
            );
        }

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

    private function exists(ConnectionInterface $connection)
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
