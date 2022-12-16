<?php

namespace Framework\Storage\SQL;

use Framework\Storage\RepositoryManager;
use PDO;

class RepositoryManagerMySQL extends RepositoryManager {

    protected PDO $pdo;

    public function __construct(array $databaseConfiguration)
    {
        $this->pdo = new PDO("mysql:host={$databaseConfiguration['hostname']};dbname={$databaseConfiguration['database']}", 
            $databaseConfiguration['login'], $databaseConfiguration['password'],
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
        );

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function registerRepository($entityClass, $repositoryClass) 
    {
        $this->repositories[$entityClass] = new $repositoryClass($this->pdo);
    }
}