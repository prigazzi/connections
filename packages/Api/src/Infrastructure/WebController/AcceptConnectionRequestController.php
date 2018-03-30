<?php
declare(strict_types=1);

namespace Vonq\Api\Infrastructure\WebController;

use Exception;
use Vonq\Api\Application\Exception\CantAcceptRelationshipException;
use Vonq\Api\Application\Exception\ConnectionAlreadyExistsException;
use Vonq\Api\Application\Service\ConnectionService;
use Vonq\Api\Domain\Model\UserId;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use \InvalidArgumentException;

class AcceptConnectionRequestController
{
    private $response;
    private $service;
    
    public function __construct(
        ResponseInterface $response,
        ConnectionService $service
    ) {
        $this->response = clone $response;
        $this->service = $service;
    }
    
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->response
            ->withHeader('Content-type', 'text/html')
            ->withStatus(201);
        
        try {
            $body = json_decode($request->getBody()->getContents());
            $userFrom = UserId::fromString($body->userFrom);
            $userTo = UserId::fromString($body->userTo);

            $this->service->acceptUserInvitation($userFrom, $userTo);
        } catch (CantAcceptRelationshipException $e) {
            $response = $response->withStatus(409, $e->getMessage());
        } catch (InvalidArgumentException $e) {
            $response = $response->withStatus(400);
        } catch (Exception $e) {
            $response = $response->withStatus(500);
        }

        return $response;
    }
}
