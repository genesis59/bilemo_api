<?php

namespace App\Controller\Smartphone;

use App\Repository\SmartphoneRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SmartphoneGetOneController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     */
    #[Route('/api/smartphones/{id}', name: 'app_get_smartphone', methods: ['GET'])]
    public function __invoke(SmartphoneRepository $smartphoneRepository, string $idSmartphone): JsonResponse
    {
        $smartphone = $smartphoneRepository->find($idSmartphone);
        if (!$smartphone) {
            throw new EntityNotFoundException();
        }
        return $this->json($smartphone, Response::HTTP_OK, [], ['groups' => 'read:smartphone']);
    }
}
