<?php
declare(strict_types=1);

namespace ApiSlim\Controllers;

use DI\Container;
use DI\NotFoundException;
use DI\DependencyException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;


abstract class A_Controller
{
    protected Container $container;
    protected mixed $pdo;
    protected mixed $view;


    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct(Container $container)
    {
        $this->pdo = $container->get('database');
        $this->view = $container->get('view');
        $this->container = $container;
    }

    /**
     * @param array $data
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    protected function render(array $data, ResponseInterface $response): ResponseInterface
    {
        $payload = json_encode($data, JSON_PRETTY_PRINT);
        $response->getBody()->write((string)$payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function getRequestBodyAsArray(Request $request): array
    {
        $requestBody = explode('&', urldecode($request->getBody()->getContents()));
        $requestBodyParsed = [];
        foreach ($requestBody as $item) {
            $itemTemp = explode('=', $item);
            $requestBodyParsed[$itemTemp[0]] = $itemTemp[1];
        }
        return $requestBodyParsed;
    }

}