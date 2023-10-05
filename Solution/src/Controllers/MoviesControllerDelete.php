<?php
declare(strict_types=1);

namespace ApiSlim\Controllers;

use DI\NotFoundException;
use DI\DependencyException;
use ApiSlim\Models\MoviesModel;
use Psr\Http\Message\ResponseInterface;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MoviesControllerDelete extends A_Controller
{
    /**
     * @OA\Delete(
     *     path="/v1/movies/{id:[0-9]+}",
     *     @OA\Response(
     *         response="200",
     *         description="movie deleted successfully"
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
    function deleteAction(Request $request, Response $response, array $args = []): ResponseInterface
    {
        $id = (int)$args['id'];
        $movies = new MoviesModel($this->container);
        $movies->deleteMovie($id);
        $responseData = [
            'code' => StatusCodeInterface::STATUS_OK,
            'message' => 'Movie has been deleted successfully!'
        ];
        return $this->render($responseData, $response);
    }

}