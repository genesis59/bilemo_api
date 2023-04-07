<?php

namespace App\Controller\Reseller;

use App\Entity\Reseller;
use App\Repository\ResellerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class CreateController extends AbstractController
{
    #[Route('/api/auth/signup', name: 'app_create_reseller', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ResellerRepository $resellerRepository,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {

        /** @var Reseller $reseller */
        $reseller = $serializer->deserialize($request->getContent(), Reseller::class, 'json');
        $reseller->setCreatedAt(new \DateTimeImmutable());
        $reseller->setRoles(['ROLE_USER']);
        $reseller->setPassword($passwordHasher->hashPassword($reseller, $reseller->getPassword()));
        $reseller->setUuid(Uuid::v4());
        $resellerRepository->save($reseller, true);

        return $this->json($reseller, Response::HTTP_CREATED, [], ['groups' => 'read:reseller']);
    }
}
