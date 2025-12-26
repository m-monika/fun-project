<?php

namespace App\Controller;

use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class MainController
{
    #[Route('/')]
    public function homepage(): Response
    {
        $response = new Response('{"message": "Welcome to the homepage!"}');
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
