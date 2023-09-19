<?php

namespace App\Controller\Customer;

use App\DTO\CustomerDto;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Exception;
use Nelmio\ApiDocBundle\Annotation\Model;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use OpenApi\Attributes as OA;

class CustomerCreateController extends AbstractController
{
    /**
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[Route('/api/customers', name: 'app_create_customer', methods: ['POST'])]
    #[OA\Response(
        response: 201,
        description: 'Création réussie',
        content: new OA\JsonContent(
            example: [
                "_links" => [
                    "self" => "/api/customers/49621b6d-67b6-4434-b2fd-96f27be9c639",
                    "create" => "/api/customers",
                    "update" => "/api/customers/49621b6d-67b6-4434-b2fd-96f27be9c639",
                    "delete" => "/api/customers/49621b6d-67b6-4434-b2fd-96f27be9c639"
                ],
                "uuid" => "49621b6d-67b6-4434-b2fd-96f27be9c639",
                "firstName" => "J'ean-Élodie Françoise",
                "lastName" => "Powlowski",
                "email" => "ralph.moen@hotmail.ll3l",
                "phoneNumber" => "(650) 234-26002",
                "street" => "Santiago Inlet2",
                "city" => "Novaville2",
                "country" => "Kyrgyz Republic2",
                "postCode" => "46399-50922",
                "createdAt" => "2023-09-08T12:34:42+02:00",
                "updatedAt" => null,
                "reseller" => [
                    "_links" => [
                        "self" => "/api/auth/signup",
                    ],
                    "email" => "connie.miller@mohr.com",
                    "company" => "Hills-Hoppe",
                    "_type" => "App\\Entity\\Customer"
                ],
                "smartphones" => [
                    [
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
                        "_type" => "App\\Entity\\Customer"
                    ]
                ],
                "_type" => "App\\Entity\\Customer"
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
        response: 405,
        description: 'Méthode non autorisée',
        content: new OA\JsonContent(
            ref: '#/components/schemas/MethodNotAllowed',
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Format d\'envoi non reconnu',
        content: new OA\JsonContent(
            ref: '#/components/schemas/NotFound',
            type: 'object'
        )
    )]
    #[OA\Post(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: new Model(type: CustomerDto::class))
        )
    )]
    #[OA\Tag(name: 'Customer')]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        CustomerRepository $customerRepository,
        ValidatorInterface $validator,
        TranslatorInterface $translator,
        TagAwareCacheInterface $cache,
        #[MapRequestPayload] CustomerDto $customerDto
    ): JsonResponse {
        /** @var Customer $customer */
        $customer = $serializer->deserialize($request->getContent(), Customer::class, 'json', [
            DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
            'groups' => 'post:customer'
        ]);
        $errors = $validator->validate($customer);
        if ($errors->count() > 0) {
            $jsonErrors = [];
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $jsonErrors['code'] = Response::HTTP_UNPROCESSABLE_ENTITY;
                $jsonErrors['message'] = $translator->trans($error->getMessageTemplate());
            }
            throw new UnprocessableEntityHttpException(
                '',
                null,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                ['errors' => $jsonErrors]
            );
        }
        $customerRepository->save($customer, true);
        $key = sprintf("customer-%s", $customer->getUuid());
        $cache->invalidateTags(['customersCache']);
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($customer, $serializer) {
                $item->expiresAfter(random_int(0, 300) + 3300);
                return $serializer->serialize($customer, 'json', [
                    'groups' => 'read:customer',
                    'type' => Customer::class
                ]);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_CREATED, [], true);
    }
}
