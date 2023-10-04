<?php
declare(strict_types=1);

namespace ApiSlim\Controllers;
error_reporting(1);


use JsonException;
use OpenApi\Generator;
use Psr\Http\Message\ResponseInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * @OA\Info(
 *     title="Slim API-Movie details",
 *     version="1.0",
 *      @OA\Contact(
 *     email="victoria.elena01@yahoo.com"
 *   )
 * )
 */
class OpenApiController
{
    /**
     * @param Request $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws JsonException
     */
    public function documentationAction(Request $request, ResponseInterface $response): ResponseInterface
    {
        $openapi = Generator::scan([__DIR__ . '/../../src']);
        return new JsonResponse(json_decode($openapi->toJson(), true, 512, JSON_THROW_ON_ERROR));
    }
}