<?php
declare(strict_types=1);

namespace Vonq\Api\Infrastructure\WebController;

use Exception;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Vonq\Api\Application\Service\ConnectionService;
use Vonq\Api\Domain\Model\UserId;
use Zend\Diactoros\Response\JsonResponse;

class UserConnectionListController
{
    /** @var JsonResponse */
    private $response;

    /** @var ConnectionService */
    private $service;

    public function __construct(JsonResponse $response, ConnectionService $service)
    {
        $this->response = clone $response;
        $this->service = $service;
    }
    
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $response = $this->response->withStatus(200);

        try {
            $userId = UserId::fromString($request->getAttribute('userId'));

            $connectionList = $this->service->retrieveConnectionsForUser($userId);

            if (count($connectionList) === 0) {
                $response = $response->withStatus(404); //Not Found
            } else {
                $response = $response->withPayload($connectionList->toArray());
            }
        } catch (InvalidArgumentException $e) {
            $response = $response->withStatus(400); //Bad Request
        } catch (Exception $e) {
            $response = $response->withStatus(500); //Internal Server Error
        }

        return $response;
    }
}
