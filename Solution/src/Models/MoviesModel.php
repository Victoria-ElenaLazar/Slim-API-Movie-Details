<?php
declare(strict_types=1);

namespace ApiSlim\Models;

use PDO;
use PDOException;
use AssertionError;

require __DIR__ . '/../../vendor/autoload.php';

class MoviesModel extends A_Model
{
    /**
     * @return array
     * return all movies from database
     */
    function findAllMovies(): array
    {
        $sql = "SELECT * FROM movies";
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $data
     * @return false|string
     * insert a new movie into the database
     */
    function insertMovie(array $data): false|string
    {
        $sql = "INSERT INTO movies (title, year, released, runtime, genre, director, actors, country, 
    poster, imdb, type) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7],
            $data[8], $data[9], $data[10]]);
        return $this->getPdo()->lastInsertId();
    }


    /**
     * @param int $id
     * @param array $data
     * @return bool
     * update movie by its ID
     */
    function updateMovie(int $id, array $data): bool
    {
        $sql = "UPDATE movies SET title=?, year=?, released=?, runtime=?, genre=?, director=?, actors=?, country=?, poster=?, imdb=?, type=? WHERE id=?";

        $stmt = $this->getPdo()->prepare($sql);

        $stmt->bindValue(1, $data['title']);
        $stmt->bindValue(2, $data['year']);
        $stmt->bindValue(3, $data['released']);
        $stmt->bindValue(4, $data['runtime']);
        $stmt->bindValue(5, $data['genre']);
        $stmt->bindValue(6, $data['director']);
        $stmt->bindValue(7, $data['actors']);
        $stmt->bindValue(8, $data['country']);
        $stmt->bindValue(9, $data['poster']);
        $stmt->bindValue(10, $data['imdb']);
        $stmt->bindValue(11, $data['type']);
        $stmt->bindValue(12, $id);

        try {
            $success = $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }

        return $success;
    }

    /**
     * @param int $id
     * @return bool
     * delete a particular movie based on ID
     */
    function deleteMovie(int $id): bool
    {
        $sql = "DELETE FROM movies WHERE id=?";
        try {
            $stmt = $this->getPdo()->prepare($sql);
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    /**
     * Partially update a movie record.
     *
     * @param int $id The ID of the movie to update.
     * @param array $updateData An associative array of fields to update.
     * @return bool Returns true on success, false on failure.
     */
    public
    function partialUpdateMovie(int $id, array $updateData): bool
    {
        if (empty($updateData)) {
            return false;
        }

        $sql = 'UPDATE movies SET ';
        $params = [];

        foreach ($updateData as $field => $value) {
            $sql .= "$field = :$field, ";
            $params[":$field"] = $value;
        }

        $sql = rtrim($sql, ', ');

        $sql .= ' WHERE id = :id';
        $params[':id'] = $id;

        $stmt = $this->getPdo()->prepare($sql);
        $result = $stmt->execute($params);

        return $result !== false;
    }

    /**
     * @param int $perPage
     * @param int $page
     * @return array
     * display a given number of movies on a particular page
     */
    public function getMoviesWithPagination(int $perPage, int $page): array
    {
        $args = [];

        $begin = ($page - 1) * $perPage;

        $sql = "SELECT * FROM movies LIMIT :perPage OFFSET :begin";

        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->bindParam(':begin', $begin, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $args[] = $row;
        }

        return $args;
    }

    /**
     * @param int $perPage
     * @param string $fieldToSort
     * @return array
     * sort the movies by fields
     */
    public function getMoviesSorted(int $perPage, string $fieldToSort): array
    {
        $validFields = [
            'title',
            'year',
            'actors',
            'genre',
            'imdb',
            'type'
        ];

        if (!in_array($fieldToSort, $validFields)) {
            throw new AssertionError("Invalid field for sorting: $fieldToSort");
        }

        $sql = "SELECT * FROM movies ORDER BY $fieldToSort ASC LIMIT :perPage";
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}