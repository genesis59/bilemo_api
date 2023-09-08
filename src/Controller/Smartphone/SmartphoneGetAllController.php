<?php

namespace App\Controller\Smartphone;

use App\Entity\Smartphone;
use App\Paginator\PaginatorService;
use App\Repository\SmartphoneRepository;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use OpenApi\Attributes as OA;

class SmartphoneGetAllController extends AbstractController
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[OA\Response(
        response: 200,
        description: 'Obtenir une liste de Smartphone paginée',
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
                                        )
                                    ]
                                ),
                                new OA\Property(
                                    property: null,
                                    ref: new Model(type: Smartphone::class, groups: ['read:customer']),
                                    type: 'object'
                                ),
                                new OA\Property(
                                    property: '_type',
                                    type: 'string',
                                    default: Smartphone::class
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
    #[OA\Tag(name: 'Smartphone')]
    #[Route('/api/smartphones', name: 'app_get_smartphones', methods: ['GET'])]
    public function __invoke(
        Request $request,
        SmartphoneRepository $smartphoneRepository,
        PaginatorService $paginatorService,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer
    ): JsonResponse {
        $key = sprintf(
            "smartphones-%s-%s-%s",
            intval($request->get('page', 1)),
            intval($request->get('limit', $this->getParameter('default_customer_per_page'))),
            $request->get('q', "")
        );
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($paginatorService, $smartphoneRepository, $serializer, $request) {
                $item->tag('smartphonesCache');
                $item->expiresAfter(random_int(0, 300) + 3300);
                $context = [
                    'groups' => 'read:smartphone',
                    'type' => Smartphone::class
                ];
                $paginatorService->create($smartphoneRepository, $request, 'app_get_smartphones');
                return $serializer->serialize($paginatorService, 'json', $context);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
