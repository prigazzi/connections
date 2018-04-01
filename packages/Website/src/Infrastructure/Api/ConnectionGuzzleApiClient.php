<?php
declare(strict_types=1);

namespace Connections\Website\Infrastructure\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;
use Connections\Website\Domain\Model\UserId;

class ConnectionGuzzleApiClient implements ConnectionApiClientInterface
{
    const BASE_URI = 'http://localhost:8888/api/connection/';

    /** @var Client */
    private $guzzle;

    public function __construct()
    {
        $this->guzzle = new Client([
            'base_uri' => self::BASE_URI
        ]);
    }

    public function retrieveRelationshipsForUser(UserId $userId)
    {
        try {
            $response = $this->guzzle->get($userId->toString());
            $connections = json_decode($response->getBody()->getContents(), true);
        } catch (TransferException $exception) {
            $connections = [];
        }

        return array_map(function (array $connection) {
            return UserId::fromString($connection['userTo']);
        }, $connections);
    }
}
