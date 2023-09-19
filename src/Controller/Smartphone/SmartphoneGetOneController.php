<?php

namespace App\Controller\Smartphone;

use App\Entity\Smartphone;
use App\Repository\SmartphoneRepository;
use App\Versioning\ApiTransformer;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
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
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[OA\Response(
        response: 200,
        description: 'Obtenir un Smartphone ciblé',
        content: new OA\JsonContent(
            example: [
                "_links" => [
                    "self" => "/api/smartphones/6e26e41a-ab9f-4900-9b1b-d3b6ec2cd3ad",
                ],
                "uuid" => "6e26e41a-ab9f-4900-9b1b-d3b6ec2cd3ad",
                "name" => "Darryl Hagenes",
                "price" => "2281.67",
                "startedAt" => "2010-05-08T21:17:04+02:00",
                "endedAt" => "2006-08-24T04:06:15+02:00",
                "createdAt" => "1987-03-02T18:44:54+01:00",
                "technology" => "5G",
                "operatingSystem" => "Mozilla/5.0 (Macintosh; PPC Mac OS X 10_7_5 rv:3.0; en-US)",
                "specificAbsorptionRateMember" => 65,
                "specificAbsorptionRateTrunk" => 23,
                "specificAbsorptionRateHead" => 89,
                "weight" => 66,
                "width" => 54,
                "height" => 7,
                "depth" => 5,
                "sparePartsAvailibility" => 23,
                "indexRepairibility" => 80,
                "ecoRatingDurability" => 48,
                "ecoRatingClimateRespect" => 6,
                "ecoRatingRepairability" => 34,
                "ecoRatingResourcesPreservation" => 69,
                "ecoRatingRecyclability" => 4,
                "microSdSlotMemory" => true,
                "romMemory" => 37,
                "callAutonomy" => 16,
                "batteryAutonomy" => 70,
                "pictures" => [
                    [
                        "uuid" => "344141ef-4d45-4762-81d5-9365e3d720ad",
                        "fileName" => "/tmp/fakerv0i4xT"
                    ]
                ],
                "range" => [
                    "uuid" => "6d5bf316-877e-4f8a-be32-c2e3985658e0",
                    "name" => "mollitia et",
                    "description" => "Rerum magni vel a architecto expedita.",
                    "brand" => [
                        "uuid" => "20d53977-89a1-4c38-b9d2-9235202486e6",
                        "name" => "Dickinson-Beier"
                    ]
                ],
                "cameras" => [
                    [
                        "uuid" => "7249328a-875f-4013-b2ea-9ef8083480c9",
                        "name" => "Viva",
                        "numericZoom" => 38,
                        "resolution" => 67,
                        "numericZoomBack" => false,
                        "flash" => false,
                        "flashBack" => true
                    ]
                ],
                "colors" => [
                    [
                        "uuid" => "d3ec8d86-dfe2-4e80-94f2-19a5e755d5cb",
                        "name" => "AliceBlue",
                        "hex" => "#a25a09"
                    ]
                ],
                "screen" => [
                    "uuid" => "ab95ef13-e53b-46c0-b2d6-3f0acf0f15c0",
                    "resolutionMainScreen" => "1920x1080",
                    "diagonal" => 15,
                    "touchScreen" => false
                ],
                "_type" => "App\\Entity\\Smartphone"
            ]
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
    #[OA\Tag(name: 'Smartphone')]
    #[Route('/api/smartphones/{uuid}', name: 'app_get_smartphone', methods: ['GET'])]
    public function __invoke(
        SmartphoneRepository $smartphoneRepository,
        string $uuid,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer
    ): JsonResponse {

        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException("", Response::HTTP_NOT_FOUND);
        }

        $key = sprintf("smartphone-%s", $uuid);
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($smartphoneRepository, $serializer, $uuid) {
                $item->expiresAfter(random_int(0, 300) + 3300);
                $smartphone = $smartphoneRepository->findOneBy(['uuid' => $uuid]);
                if (!$smartphone) {
                    throw new EntityNotFoundException("", Response::HTTP_NOT_FOUND);
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
