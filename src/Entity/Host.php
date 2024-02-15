<?php

namespace src\Entity;


use AllowDynamicProperties;
use InvalidArgumentException;
use src\Database;

#[AllowDynamicProperties] class Host
{
    public const STATUS_FALSE = 0;
    public const STATUS_TRUE = 1;

    public Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function hosts(): array
    {
        $sql = 'SELECT * FROM hosts;';

        return $this->database->fetchAll($sql);
    }

    public function getHostById(int $id): array
    {
        $sql = 'SELECT * FROM hosts WHERE id = ' . $id . ';';

        return $this->database->fetch($sql);
    }

    private function validationDomain(string $data): string
    {
        $name = preg_replace('/^(https?:\/\/)/', '', $data);

        if (!filter_var($data, FILTER_VALIDATE_DOMAIN)) {
            throw new InvalidArgumentException('Domain "' . $name . '" is not valid.');
        }

        return $name;
    }

    private function validateIP(string $data): string
    {
        if (!filter_var($data, FILTER_VALIDATE_IP)) {
            throw new InvalidArgumentException('IP "' . $data . '" is not valid.');
        }

        return $data;
    }

    private function validationName(string $data): string
    {
        try {
            $data = $this->validationDomain($data);

            if (!$data) {
                $data = $this->validateIP($data);
            }

        } catch (\InvalidArgumentException $exception) {
            throw new \Exception($exception->getMessage());
        }

        return $data;
    }

    /**
     * @throws \Exception
     */
    public function create(string $name, string $description): string
    {
        try {
            $sql = 'INSERT INTO hosts (`name`, `description`, `status`, `created`, `updated`) VALUES (?, ?, ?, ?, ?)';
            $params = [$this->validationName($name), $description, self::STATUS_FALSE, date('Y-m-d H:i:s'), null];

            $this->database->query($sql, $params);

            return true;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    public function update(array $host): string
    {
        $data = $this->getHostById($host['id']);

        $sql = 'UPDATE hosts SET
            name = :name,
            description = :description,
            status = :status,
            updated = :updated
        WHERE id = :id';

        $name = !empty($host['name']) ? $host['name'] : $data['name'];

        $params = [
            ':name' => $this->validationName($name),
            ':description' => !empty($host['description']) ? $host['description'] : $data['description'],
            ':status' => $host["status"] ?? $data["status"],
            ':updated' => date('Y-m-d H:i:s'),
            ':id' => $host['id'],
        ];

        $this->database->query($sql, $params);

        return 'Success updated ' . $host['id'];
    }

    /**
     * @throws \Exception
     */
    public function delete(int $id): string
    {
        $sql = 'DELETE FROM hosts WHERE id = ' . $id . ';';

        $this->database->query($sql);

        return 'Success deleted ' . $id;
    }
}