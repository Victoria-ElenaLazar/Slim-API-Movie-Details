<?php
declare(strict_types=1);

namespace ApiSlim\Middlewares;

use DI\Container;
use Slim\Psr7\Response;
use DI\NotFoundException;
use DI\DependencyException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class AfterMiddleware
{
    private Container $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param RequestHandler $handler
     * @return ResponseInterface
     */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        try {
            $this->logResponse($handler->handle($request));
        } catch (DependencyException|NotFoundException $e) {
        }

        return $handler->handle($request);
    }

    /**
     * @param Response $response
     * @return void
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function logResponse(ResponseInterface $response): void
    {
        $headers = "HEADERS: " . json_encode($response->getHeaders()) . PHP_EOL;
        $body = "BODY: " . (string)$response->getBody() . PHP_EOL;
        $statusCode = "STATUS CODE: " . $response->getStatusCode() . PHP_EOL;
        $responseLogFileName = __DIR__ . "/../../" . $this->container->get('settings')['RESPONSE_LOG_FILE_NAME'];

        file_put_contents($responseLogFileName, $statusCode, FILE_APPEND);
        file_put_contents($responseLogFileName, $headers, FILE_APPEND);
        file_put_contents($responseLogFileName, $body, FILE_APPEND);
        file_put_contents($responseLogFileName, PHP_EOL, FILE_APPEND);
        file_put_contents($responseLogFileName, PHP_EOL, FILE_APPEND);
    }
}