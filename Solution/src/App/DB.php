<?php
declare(strict_types=1);

namespace ApiSlim\App;

use PDO;
use DI\Container;
use DI\NotFoundException;
use DI\DependencyException;

class DB
{
    public ?PDO $connection = null;

    /**
     * @throws NotFoundException
     * @throws DependencyException
     */
    public function __construct(Container $container)
    {
        if ($this->connection == null) {
            $dbHost = $container->get('settings')['DB_HOST'];
            $dbName = $container->get('settings')['DB_NAME'];
            $dbUser = $container->get('settings')['DB_USER'];
            $dbPassword = $container->get('settings')['DB_PASSWORD'];
            $dsn = "mysql:host=$dbHost;dbname=$dbName";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $this->connection = new PDO($dsn, $dbUser, $dbPassword, $options);
        }
    }
}
