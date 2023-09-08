<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Paginator\PaginatorService;
use App\Repository\CustomerRepository;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Property;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference;
use phpDocumentor\Reflection\Type;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use OpenApi\Attributes as OA;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CustomerGetAllController extends AbstractController
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[Route('/api/customers', name: 'app_get_customers', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Obtenir une liste d\'utilisateurs paginée',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    new OA\Property(
                        property: 'pagination',
                        properties: [
                            new OA\Property(
                                property: 'current_page_number',
                                type: 'integer',
                                readOnly: true
                            ),
                            new OA\Property(
                                property: 'number_items_per_page',
                                type: 'integer',
                                readOnly: true
                            ),
                            new OA\Property(
                                property: 'total_items_count',
                                type: 'integer',
                                readOnly: true
                            ),
                            new OA\Property(
                                property: 'first_page_link',
                                type: 'string',
                                readOnly: true
                            ),
                            new OA\Property(
                                property: 'last_page_link',
                                type: 'string',
                                readOnly: true
                            ),
                            new OA\Property(
                                property: 'previous_page_link',
                                type: 'string',
                                readOnly: true
                            ),
                            new OA\Property(
                                property: 'next_page_link',
                                type: 'string',
                                readOnly: true
                            )
                        ],
                        type: 'object'
                    ),
                    new OA\Property(
                        property: 'items',
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
                            type: 'object'
                        )
                    )
                ],
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
    #[OA\Parameter(name: 'page', description: 'Numéro de la page', in: 'query')]
    #[OA\Parameter(name: 'limit', description: 'Nombre de Customer par page', in: 'query')]
    #[OA\Tag(name: 'Customer')]
    public function __invoke(
        Request $request,
        CustomerRepository $customerRepository,
        PaginatorService $paginatorService,
        SerializerInterface $serializer,
        TagAwareCacheInterface $cache,
    ): JsonResponse {
        $key = sprintf(
            "customers-%s-%s-%s",
            intval($request->get('page', 1)),
            intval($request->get('limit', $this->getParameter('default_customer_per_page'))),
            $request->get('q', "")
        );
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($paginatorService, $customerRepository, $serializer, $request) {
                $paginatorService->create($customerRepository, $request, 'app_get_customers');
                $item->expiresAfter(random_int(0, 300) + 3300);
                $item->tag('customersCache');
                return $serializer->serialize($paginatorService, 'json', [
                    'groups' => 'read:customer',
                    'type' => Customer::class
                ]);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
