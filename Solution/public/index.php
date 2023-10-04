<?php
declare(strict_types=1);

use DI\Container;
use Dotenv\Dotenv;
use ApiSlim\App\DB;
use Slim\Psr7\Response;
use Slim\Views\PhpRenderer;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use ApiSlim\Middlewares\AfterMiddleware;
use ApiSlim\Middlewares\BeforeMiddleware;
use ApiSlim\Controllers\ExceptionController;


require __DIR__ . '/../vendor/autoload.php';


$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();

$container->set('settings', function () {
    $dotenv = Dotenv::createImmutable(__DIR__ . "/../");
    $dotenv->safeLoad();
    return $_ENV;
});

$container->set('database', function () use ($container) {
    $db = new DB($container);
    return $db->connection;
});


$container->set('view', function () {
    return new PhpRenderer(__DIR__ . "/../src/Views");
});

$app->group('/v1', function (RouteCollectorProxy $group) {

    $group->get('/movies', '\ApiSlim\Controllers\MoviesController:indexAction');
    $group->post('/movies', '\ApiSlim\Controllers\MoviesControllerCreate:createAction');
    $group->put('/movies/{id:[0-9]+}', '\ApiSlim\Controllers\MoviesControllerUpdate:updateAction');
    $group->delete('/movies/{id:[0-9]+}', '\ApiSlim\Controllers\MoviesControllerDelete:deleteAction');
    $group->patch('/movies/{id:[0-9]+}', '\ApiSlim\Controllers\MoviesControllerPartial:partialAction');
    $group->get('/movies/{page}/{perPage}', '\ApiSlim\Controllers\MoviesControllerPagination:indexWithPagination');
    $group->get('/movies/{perPage}/sort/{fieldToSort}', '\ApiSlim\Controllers\MoviesControllerSorted:indexSorted');
    $group->get('/apidocs', '\ApiSlim\Controllers\OpenAPIController:documentationAction');
})->add(new BeforeMiddleware($container))->add(new AfterMiddleware($container));

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$errorMiddleware->setErrorHandler(
    Slim\Exception\HttpNotFoundException::class,
    function (Psr\Http\Message\ServerRequestInterface $request) use ($container) {
        $controller = new ExceptionController($container);
        return $controller->notFound($request, new Response());
    }
);

$app->run();
