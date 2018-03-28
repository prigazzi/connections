<?php
declare(strict_types=1);

namespace Vonq\Api\Infrastructure\WebController;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserConnectionListController
{
    private $response;
    
    public function __construct(ResponseInterface $response)
    {
        $this->response = clone $response;
    }
    
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $userId = $request->getAttribute('userId');
        
        $response = $this->response->withHeader('Content-type', 'text/html');
        $response->getBody()->write("List of User Connections for user {$userId}");

        return $response;
    }
}
