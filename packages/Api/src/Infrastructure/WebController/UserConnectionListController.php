<?php
declare(strict_types=1);

namespace Vonq\Api\Infrastructure\WebController;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vonq\Api\Application\Service\ConnectionService;
use Vonq\Api\Domain\Model\UserId;

class UserConnectionListController
{
    /** @var ResponseInterface */
    private $response;

    /** @var ConnectionService */
    private $service;

    public function __construct(ResponseInterface $response, ConnectionService $service)
    {
        $this->response = clone $response;
        $this->service = $service;
    }
    
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->response
            ->withHeader('Content-type', 'text/html')
            ->withStatus(200);
        $userId = UserId::fromString($request->getAttribute('userId'));

        $connectionList = $this->service->retrieveConnectionsForUser($userId);
        
        $response->getBody()->write(var_dump($connectionList->toArray()));

        return $response;
    }
}
