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

require __DIR__ . '/../../vendor/autoload.php';

class BeforeMiddleware
{
    private array $validtokens = [
        '' => 1,
        'user2Token' => 2
    ];
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
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $token = $this->getTokenFromHeader($request);
        if (!$this->isValidToken($token)) {
            return new Response(401);
        }

        $headers = "HEADERS: " . json_encode($request->getHeaders()) . PHP_EOL;
        $body = "BODY: " . $request->getBody() . PHP_EOL;
        $uri = "URI: " . $request->getUri() . PHP_EOL;

        if ($this->container !== null) {
            $requestLogFileName = __DIR__ . "/../../" . $this->container->get('settings')['REQUEST_LOG_FILE_NAME'];
            file_put_contents($requestLogFileName, $uri, FILE_APPEND);
            file_put_contents($requestLogFileName, $headers, FILE_APPEND);
            file_put_contents($requestLogFileName, $body, FILE_APPEND);
            file_put_contents($requestLogFileName, PHP_EOL, FILE_APPEND);
            file_put_contents($requestLogFileName, PHP_EOL, FILE_APPEND);
        }

        return $handler->handle($request);
    }

    /**
     * @param Request $request
     * @return array|false|string
     */
    private function getTokenFromHeader(Request $request): array|false|string
    {
        $headers = $request->getHeader('Authorization');
        $authHeader = reset($headers);
        $token = str_replace('Bearer ', '', $authHeader);

        return $token;
    }

    /**
     * @param $token
     * @return bool
     * check if the authentication token is valid
     */
    private function isValidToken($token): bool
    {
        return isset($this->validtokens[$token]);
    }
}
