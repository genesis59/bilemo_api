<?php

namespace App\Controller\Smartphone;

use App\Entity\Smartphone;
use App\Repository\SmartphoneRepository;
use App\Versioning\ApiTransformer;
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

class SmartphoneGetOneController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[OA\Response(
        response: 200,
        description: 'Obtenir un Smartphone ciblé',
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
                        ref: new Model(type: Smartphone::class, groups: ['read:customer']),
                        type: 'object'
                    ),
                    new OA\Property(
                        property: '_type',
                        type: 'string',
                        default: Smartphone::class
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
    #[OA\Tag(name: 'Smartphone')]
    #[Route('/api/smartphones/{uuid}', name: 'app_get_smartphone', methods: ['GET'])]
    public function __invoke(
        SmartphoneRepository $smartphoneRepository,
        string $uuid,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer,
        ApiTransformer $smartphoneVersionManager
    ): JsonResponse {

        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException();
        }

        $key = sprintf("smartphone-%s", $uuid);
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($smartphoneRepository, $serializer, $uuid) {
                $item->expiresAfter(random_int(0, 300) + 3300);
                $smartphone = $smartphoneRepository->findOneBy(['uuid' => $uuid]);
                if (!$smartphone) {
                    throw new EntityNotFoundException();
                }
                $context = [
                    'groups' => 'read:smartphone',
                    'type' => Smartphone::class
                ];
                return $serializer->serialize($smartphone, 'json', $context);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
