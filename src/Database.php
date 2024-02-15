<?php

namespace src;

use PDO;
use PDOException;
use PDOStatement;

class Database
{
    private string $host;
    private string $db_name;
    private string $username;
    private string $password;
    private string $charset;

    private PDO $pdo;

    public function __construct(array $configs)
    {
        $this->host = $configs['host'];
        $this->db_name = $configs['db_name'];
        $this->username = $configs['username'];
        $this->password = $configs['password'];
        $this->charset = $configs['charset'];

        $this->connection();
    }

    private function connection(): void
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * @throws \Exception
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement;
    }

    public function fetchAll(string $sql, array $params = []): array
    {
        $statement = $this->query($sql, $params);
        return $statement->fetchAll();
    }

    public function fetch(string $sql, array $params = []): array
    {
        $statement = $this->query($sql, $params);
        return $statement->fetch();
    }

    public function lastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }

    public function closeConnection(): void
    {
        $this->pdo = null;
    }
}
