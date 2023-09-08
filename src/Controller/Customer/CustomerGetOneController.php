<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use OpenApi\Attributes as OA;

class CustomerGetOneController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[OA\Response(
        response: 200,
        description: 'Obtenir un Customer ciblé',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(
                        property: '_links',
                        properties: [
                            new OA\Property(
                                property: 'self',
                                type: 'string',
                                readOnly: true
                            ),
                            new OA\Property(
                                property: 'create',
                                type: 'string',
                                readOnly: true
                            ),
                            new OA\Property(
                                property: 'update',
                                type: 'string',
                                readOnly: true
                            ),
                            new OA\Property(
                                property: 'delete',
                                type: 'string',
                                readOnly: true
                            )
                        ]
                    ),
                    new OA\Property(
                        property: null,
                        ref: new Model(type: Customer::class, groups: ['read:customer']),
                        type: 'object'
                    ),
                    new OA\Property(
                        property: '_type',
                        type: 'string',
                        default: Customer::class
                    ),
                ],
                type: 'object',
            )
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
        description: 'Ces données n\'existe pas',
        content: new OA\JsonContent(
            ref: '#/components/schemas/NotFound',
            type: 'object'
        )
    )]
    #[OA\Tag(name: 'Customer')]
    #[Route('/api/customers/{uuid}', name: 'app_get_customer', methods: ['GET'])]
    public function __invoke(
        string $uuid,
        CustomerRepository $customerRepository,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer
    ): JsonResponse {
        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException();
        }
        $key = sprintf("customer-%s", $uuid);
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($customerRepository, $serializer, $uuid) {
                $customer = $customerRepository->findOneBy(['uuid' => $uuid, 'reseller' => $this->getUser()]);
                if (!$customer) {
                    throw new EntityNotFoundException();
                }
                $item->expiresAfter(random_int(0, 300) + 3300);
                return $serializer->serialize($customer, 'json', [
                    'groups' => 'read:customer',
                    'type' => Customer::class
                ]);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
