<?php

namespace Api\Middleware;

use Laminas\Stratigility\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Http\PhpEnvironment\Request;

class ApiKeyMiddleware implements MiddlewareInterface
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $apiKey = $request->getHeaderLine('API-Key');

        if ($apiKey !== $this->apiKey) {
            return new JsonResponse(['status' => 'error', 'message' => 'Unauthorized'], 401);
        }

        return $handler->handle($request);
    }
}
