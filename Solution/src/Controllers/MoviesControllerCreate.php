<?php
declare(strict_types=1);

namespace ApiSlim\Controllers;

use ApiSlim\Models\Imdb;
use ApiSlim\Models\Year;
use ApiSlim\Models\Type;
use ApiSlim\Models\Genre;
use ApiSlim\Models\Title;
use ApiSlim\Models\Actors;
use ApiSlim\Models\Poster;
use ApiSlim\Models\Runtime;
use ApiSlim\Models\Country;
use ApiSlim\Models\Director;
use ApiSlim\Models\Released;
use ApiSlim\Models\MoviesModel;

use DI\NotFoundException;
use DI\DependencyException;
use Assert\AssertionFailedException;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MoviesControllerCreate extends A_Controller
{
    /**
     * @OA\Post(
     *     path="/v1/movies",
     *      @OA\RequestBody(
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
     *         description="create a new movie"
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
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function createAction(Request $request, Response $response): ResponseInterface
    {
        $requestBody = $request->getParsedBody();

        $title = filter_var(trim($requestBody['title']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $year = filter_var($requestBody['year'], FILTER_SANITIZE_NUMBER_INT);
        $released = filter_var(trim($requestBody['released']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $runtime = filter_var(trim($requestBody['runtime']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $genre = filter_var(trim($requestBody['genre']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $director = filter_var(trim($requestBody['director']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $actors = filter_var(trim($requestBody['actors']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $country = filter_var(trim($requestBody['country']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $poster = filter_var($requestBody['poster'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $imdb = filter_var($requestBody['imdb'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $type = filter_var(trim($requestBody['type']), FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $movies = new MoviesModel($this->container);

        try {
            $movieId = $movies->insertMovie(
                [
                    new Title($title),
                    new Year((int)$year),
                    new Released($released),
                    new Runtime($runtime),
                    new Genre($genre),
                    new Director($director),
                    new Actors($actors),
                    new Country($country),
                    new Poster($poster),
                    new Imdb((float)$imdb),
                    new Type($type)
                ]
            );
        } catch (AssertionFailedException $e) {

            $responseData = [
                'code' => StatusCodeInterface::STATUS_BAD_REQUEST,
                'message' => $e->getMessage(),
            ];

            $response = new JsonResponse($responseData, StatusCodeInterface::STATUS_BAD_REQUEST);
            return $this->render($responseData, $response);
        }

        $responseData = [
            'code' => StatusCodeInterface::STATUS_OK,
            'message' => 'Movie has been published.',
            'movie_id' => $movieId
        ];

        return $this->render($responseData, $response);
    }
}