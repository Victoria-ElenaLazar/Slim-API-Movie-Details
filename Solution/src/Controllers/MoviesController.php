<?php
declare(strict_types=1);

namespace ApiSlim\Controllers;

use ApiSlim\Models\MoviesModel;

use DI\Container;
use DI\NotFoundException;
use DI\DependencyException;
use OpenApi\Annotations as OA;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class OpenApi
{
}

class MoviesController extends A_Controller
{
    protected Container $container;
    private MoviesModel $model;
    private MoviesModel $moviesModel;
    private $logger;

    /**
     * @OA\Get(
     *     path="/v1/movies",
     *     @OA\Response(
     *         response="200",
     *         description="display all movies"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="not found"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Internal server error"
     *     )
     * )
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws DependencyException
     * @throws NotFoundException
     */

    public function indexAction(Request $request, ResponseInterface $response): ResponseInterface
    {
        $movies = new MoviesModel($this->container);
        $data = $movies->findAllMovies();
        return $this->render($data, $response);
    }
}

