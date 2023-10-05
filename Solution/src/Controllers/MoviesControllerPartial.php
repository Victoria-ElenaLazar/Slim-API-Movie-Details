<?php
declare(strict_types=1);

namespace ApiSlim\Controllers;

use Slim\Logger;
use DI\NotFoundException;
use DI\DependencyException;
use ApiSlim\Models\MoviesModel;
use Assert\AssertionFailedException;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MoviesControllerPartial extends A_Controller
{
    private Logger $logger;

    /**
     * @OA\Patch(
     *     path="/v1/movies/{id:[0-9]+}",
     *     @OA\RequestBody(
     *         description="Input data format",
     *         required=true,
     *      @OA\MediaType(
     *             mediaType="application/json",
     *      @OA\Schema(
     *                 type="object",
     *     @OA\Property( property="title",
     *                   description="Title of the new movie",
     *                   type="string"
     *                 ),
     *      @OA\Property( property="year",
     *                   description="year of the movie",
     *                   type="string"
     *                 ),
     *      @OA\Property( property="released",
     *                   description="the date when movie was released",
     *                   type="string"
     *                 ),
     *      @OA\Property( property="runtime",
     *                   description="how long is the movie",
     *                   type="string"
     *                 ),
     *      @OA\Property( property="director",
     *                   description="the director of the movie",
     *                   type="string"
     *                 ),
     *      @OA\Property( property="actors",
     *                   description="the actors playing in movie",
     *                   type="string"
     *                 ),
     *      @OA\Property( property="country",
     *                   description="country of origin",
     *                   type="string"
     *                 ),
     *      @OA\Property( property="poster",
     *                   description="image of the movie",
     *                   type="string"
     *                 ),
     *      @OA\Property( property="imdb",
     *                   description="movie rating",
     *                   type="string"
     *                 ),
     *      @OA\Property( property="type",
     *                   description="movie, tv series, cartoon",
     *                   type="string"
     *                 ),
     *      @OA\Property( property="genre",
     *                   description="Horror, Action, Crime, Thriller",
     *                   type="string"
     *                 ),
     *               )
     *            )
     *         ),
     *     @OA\Response(
     *         response="200",
     *         description="movie updated successfully"
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
    public function partialAction(Request $request, Response $response, array $args): ResponseInterface
    {
        $id = (int)$args['id'];
        $updateData = $request->getParsedBody();

        if (empty($updateData)) {
            $responseData = [
                'code' => StatusCodeInterface::STATUS_BAD_REQUEST,
                'message' => 'Update data is empty.'
            ];
            $response = new JsonResponse($responseData, StatusCodeInterface::STATUS_BAD_REQUEST);
            return $this->render($responseData, $response);
        }

        $movies = new MoviesModel($this->container);

        try {
            $success = $movies->partialUpdateMovie($id, $updateData);

            if (!$success) {
                $responseData = [
                    'code' => StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
                    'message' => 'Failed to update the movie.'
                ];
                return new JsonResponse($responseData, StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR);
            }
        } catch (AssertionFailedException $e) {
            $this->logger->error('Error updating movie: ' . $e->getMessage());

            $responseData = [
                'code' => StatusCodeInterface::STATUS_BAD_REQUEST,
                'message' => $e->getMessage()
            ];

            $response = new JsonResponse($responseData, StatusCodeInterface::STATUS_BAD_REQUEST);
            return $this->render($responseData, $response);
        }

        $responseData = [
            'code' => StatusCodeInterface::STATUS_OK,
            'message' => 'Movie has been updated successfully!'
        ];

        return new JsonResponse($responseData, StatusCodeInterface::STATUS_OK);
    }
}