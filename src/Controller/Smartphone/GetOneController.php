<?php

namespace App\Controller\Smartphone;

use App\Entity\Smartphone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetOneController extends AbstractController
{
    #[Route('/api/smartphones/{id}', name: 'app_get_smartphone', methods: ['GET'])]
    public function __invoke(Smartphone $smartphone): JsonResponse
    {
        return $this->json($smartphone, Response::HTTP_OK, [], ['groups' => 'read:smartphone']);
    }
}
