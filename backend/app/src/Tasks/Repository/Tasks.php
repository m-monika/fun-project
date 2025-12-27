<?php

namespace App\Tasks\Repository;

use Doctrine\DBAL\Connection;

class Tasks
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function getAllTasks(): array
    {
        $sql = 'SELECT id, title, description, completed FROM tasks';

        return $this->connection->fetchAllAssociative($sql);
    }

    public function find(int $id): ?array
    {
        $sql = 'SELECT id, title, description, completed FROM tasks WHERE id = :id';

        $task = $this->connection->fetchAssociative($sql, [
            'id' => $id,
        ]);

        return $task ?: null;
    }

    public function addTask(string $task, string $description): bool
    {
        return (bool) $this->connection->insert('tasks', [
            'title' => $task,
            'description' => $description,
            'completed' => 0,
        ]);
    }

    public function updateTask(int $id, string $task, string $description): bool
    {
        return (bool) $this->connection->update(
            'tasks',
            [
                'title' => $task,
                'description' => $description,
            ],
            [
                'id' => $id,
            ]
        );
    }

    public function deleteTask(int $id): bool
    {
        return (bool) $this->connection->delete('tasks', [
            'id' => $id,
        ]);
    }
}
