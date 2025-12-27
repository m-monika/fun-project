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
        return $this->json(['message' => '!!TODO create task!!']);
    }
}
