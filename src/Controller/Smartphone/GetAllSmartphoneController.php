<?php

namespace App\Controller\Smartphone;

use App\Repository\SmartphoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class GetAllSmartphoneController extends AbstractController
{
    #[Route('/api/smartphones', name: 'app_get_smartphones')]
    public function __invoke(SmartphoneRepository $smartphoneRepository, SerializerInterface $serializer): JsonResponse
    {
        $smartphones = $smartphoneRepository->findAll();
        return $this->json($smartphones, Response::HTTP_OK, [], ['groups' => 'read:smartphone']);
    }
}
