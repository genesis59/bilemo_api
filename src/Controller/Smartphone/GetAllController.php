<?php

namespace App\Controller\Smartphone;

use App\Repository\SmartphoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GetAllController extends AbstractController
{
    #[Route('/api/smartphones', name: 'app_get_smartphones', methods: ['GET'])]
    public function __invoke(SmartphoneRepository $smartphoneRepository): JsonResponse
    {
        $smartphones = $smartphoneRepository->findAll();
        return $this->json($smartphones, Response::HTTP_OK, [], ['groups' => 'read:smartphone']);
    }
}
