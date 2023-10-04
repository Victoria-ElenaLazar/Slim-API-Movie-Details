<?php
declare(strict_types=1);

namespace ApiSlim\Controllers;

use Slim\Logger;
use DI\NotFoundException;
use DI\DependencyException;
use ApiSlim\Models\MoviesModel;
use Assert\AssertionFailedException;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MoviesControllerPagination extends A_Controller
{
    private Logger $logger;

    /**
     * @OA\Get(
     *     path="/v1/movies/{page}/{perPage}",
     *     @OA\Response(
     *         response="200",
     *         description="display movies per page"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="not found"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="bad request"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error"
     *     )
     * )
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function indexWithPagination(Request $request, Response $response, array $args): Response
    {
        $page = (int)$args['page'];
        $perPage = (int)$args['perPage'];

        $movies = new MoviesModel($this->container);

        try {
            $moviesData = $movies->getMoviesWithPagination($perPage, $page);

            if (!empty($moviesData)) {
                $responseData = [
                    'code' => StatusCodeInterface::STATUS_OK,
                    'message' => "Movies on the current page",
                    'data' => $moviesData,
                ];
            } else {
                $responseData = [
                    'code' => StatusCodeInterface::STATUS_NOT_FOUND,
                    'message' => "No movies found on this page",
                ];
            }
        } catch (AssertionFailedException $e) {
            $this->logger->error("Error displaying movies with pagination: " . $e->getMessage());
            $responseData = [
                'code' => StatusCodeInterface::STATUS_BAD_REQUEST,
                'message' => "Failed to display movies on this page"
            ];
        }

        $response = new JsonResponse($responseData, $responseData['code']);
        return $this->render($responseData, $response);
    }
}