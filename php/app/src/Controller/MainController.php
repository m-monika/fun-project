<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class MainController extends AbstractController
{
    #[Route('/')]
    public function homepage(): Response
    {
        $json = ["message" => "Welcome to the homepage!"];

        return $this->json($json);
    }
}
