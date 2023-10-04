<?php
declare(strict_types=1);

namespace ApiSlim\Controllers;

use DI\NotFoundException;
use DI\DependencyException;
use ApiSlim\Middlewares\AfterMiddleware;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../../vendor/autoload.php';

class ExceptionController extends A_Controller
{
    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function notFound(Request $request, Response $response): JsonResponse
    {
        $middleware = new AfterMiddleware($this->container);
        $payload = ['status' => 404, 'message' => 'not found'];
        $response = new JsonResponse($payload, 404);
        $middleware->logResponse($response);
        return $response;
    }
}