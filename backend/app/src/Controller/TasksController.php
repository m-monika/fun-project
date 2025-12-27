<?php

namespace App\Controller;

use App\Tasks\Repository\Tasks;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TasksController extends AbstractController
{
    #[Route('/tasks', methods: ['GET'])]
    public function getList(Tasks $tasks): Response
    {
        $json = $tasks->getAllTasks();

        return $this->json($json);
    }

    #[Route('/tasks/{id<\d+>}', methods: ['GET'])]
    public function get(int $id, Tasks $tasks): Response
    {
        $json = $tasks->find($id);

        if (!$json) {
            throw $this->createNotFoundException('Not found');
        }

        return $this->json($json);
    }

    #[Route('/tasks', methods: ['POST'])]
    public function create(Request $request, Tasks $tasks): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json(
                ['error' => 'Invalid JSON body'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $title = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');

        // --- Validation ---
        if ($title === '') {
            return $this->json(
                ['error' => 'Title is required'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if (mb_strlen($title) > 255) {
            return $this->json(
                ['error' => 'Title must be at most 255 characters'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($description === '') {
            return $this->json(
                ['error' => 'Description is required'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        // --- Create task ---
        $created = $tasks->addTask($title, $description);

        if (!$created) {
            return $this->json(
                ['error' => 'Failed to create task'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        return $this->json(
            ['message' => 'Task created successfully'],
            Response::HTTP_CREATED
        );
    }
}
