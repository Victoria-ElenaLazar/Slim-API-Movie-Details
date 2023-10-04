<?php
declare(strict_types=1);

namespace ApiSlim\Models;

use PDO;
use DI\Container;
use DI\NotFoundException;
use DI\DependencyException;

abstract class A_Model
{
    protected ?PDO $pdo;

    abstract function findAllMovies(): array;

    abstract function insertMovie(array $data): false|string;

    abstract function updateMovie(int $id, array $data): bool;

    abstract function deleteMovie(int $id): bool;

    abstract function partialUpdateMovie(int $id, array $updateData): bool;

    abstract function getMoviesWithPagination(int $perPage, int $page): array;

    abstract function getMoviesSorted(int $perPage, string $fieldToSort): array;

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    public function __construct(Container $container)
    {
        $this->pdo = $container->get('database');
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

}