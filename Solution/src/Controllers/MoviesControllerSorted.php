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

class MoviesControllerSorted extends A_Controller
{
    private Logger $logger;

    /**
     * @OA\Get(
     *     path="/v1/movies/{perPage}/sort/{fieldToSort}",
     *     @OA\Response(
     *         response="200",
     *         description="display movies sorted by fields"
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
    public function indexSorted(Request $request, Response $response, array $args): Response
    {
        $perPage = (int)$args['perPage'];
        $fieldToSort = $args['fieldToSort'];
        try {
            $movies = new MoviesModel($this->container);
            $sortedMovies = $movies->getMoviesSorted($perPage, $fieldToSort);
            if (!empty($sortedMovies)) {
                $responseData = [
                    'code' => StatusCodeInterface::STATUS_OK,
                    'message' => "Here are your movies sorted by $fieldToSort",
                    'data' => $sortedMovies
                ];
            } else {
                $responseData = [
                    'code' => StatusCodeInterface::STATUS_NOT_FOUND,
                    'message' => "No movies found for sorting by $fieldToSort"
                ];
            }
        } catch (AssertionFailedException $e) {
            $this->logger->error("Error sorting movies: " . $e->getMessage());
            $responseData = [
                'code' => StatusCodeInterface::STATUS_BAD_REQUEST,
                'message' => "Couldn't sort the movies by $fieldToSort"
            ];
        }
        $response = new JsonResponse($responseData, $responseData['code']);
        return $this->render($responseData, $response);
    }
}