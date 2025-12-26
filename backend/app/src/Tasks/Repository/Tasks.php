<?php

namespace App\Tasks\Repository;

class Tasks
{
    public function getAllTasks(): array
    {
        // TODO

        return [
            ['id' => 1, 'title' => 'Task 1', 'completed' => false],
            ['id' => 2, 'title' => 'Task 2', 'completed' => true],
            ['id' => 3, 'title' => 'Task 3', 'completed' => false],
        ];
    }

    public function find(int $id): ?array
    {
        // TODO
        if ($id < 1 || $id > 3) {
            return null;
        }

        return ['id' => $id, 'title' => 'Task '.$id, 'completed' => false];
    }

    public function addTask(string $task, string $description): bool
    {
        // TODO

        return true;
    }

    public function updateTask(int $id, string $task, string $description): bool
    {
        // TODO

        return true;
    }

    public function deleteTask(int $id): bool
    {
        // TODO

        return true;
    }
}
