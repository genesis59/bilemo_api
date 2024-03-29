<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityNotFoundException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Customer')]
class CustomerDeleteController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     * @throws EntityNotFoundException
     */
    #[OA\Response(
        response: 204,
        description: 'Suppression réussie'
    )]
    #[OA\Response(
        response: 400,
        description: 'Mauvaise requête',
        content: new OA\JsonContent(
            ref: '#/components/schemas/BadRequest',
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'Identifiants erronés',
        content: new OA\JsonContent(
            ref: '#/components/schemas/InvalidCredentials',
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Cette ressource n\'existe pas',
        content: new OA\JsonContent(
            ref: '#/components/schemas/NotFound',
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 405,
        description: 'Méthode non autorisée',
        content: new OA\JsonContent(
            ref: '#/components/schemas/MethodNotAllowed',
            type: 'object'
        )
    )]
    #[Route('/api/customers/{uuid}', name: 'app_delete_customer', methods: ['DELETE'])]
    public function __invoke(
        CustomerRepository $customerRepository,
        string $uuid,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException("", Response::HTTP_NOT_FOUND);
        }
        /** @var Customer $customer */
        $customer = $customerRepository->findOneBy(['uuid' => $uuid,'reseller' => $this->getUser()]);
        if ($customer == null) {
            throw new EntityNotFoundException("", Response::HTTP_NOT_FOUND);
        }
        $cache->delete(sprintf("customer-%s", $uuid));
        $cache->invalidateTags(['customersCache']);
        $customerRepository->remove($customer, true);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
