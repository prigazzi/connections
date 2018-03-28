<?php
declare(strict_types=1);

namespace Vonq\Api\Infrastructure\WebController;

use Psr\Http\Message\ResponseInterface;

class TestController
{
    private $response;
    
    public function __construct(ResponseInterface $response)
    {
        $this->response = clone $response;
    }
    
    public function __invoke(): ResponseInterface
    {
        $response = $this->response->withHeader('Content-type', 'text/html');
        $response = $response->withHeader('Location', 'http://google.com.ar');
        $response->getBody()->write('Hi from a nice Controller');

        return $response;
    }
}
