<?php

namespace App\Controller\Customer;

use App\DTO\CustomerDto;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
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
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use OpenApi\Attributes as OA;

class CustomerUpdateController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[OA\Response(
        response: 200,
        description: 'Modification réussie',
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
        response: 400,
        description: 'Mauvaise requête',
        content: new OA\JsonContent(
            ref: '#/components/schemas/BadRequest',
            type: 'object'
        )
    )]
    #[OA\Response(
        response: 404,
        description: 'Ces données n\'existe pas',
        content: new OA\JsonContent(
            ref: '#/components/schemas/UnprocessableEntity',
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
    #[OA\Put(
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: new Model(type: CustomerDto::class))
        )
    )]
    #[OA\Tag(name: 'Customer')]
    #[Route('/api/customers/{uuid}', name: 'app_update_customer', methods: ['PUT'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        CustomerRepository $customerRepository,
        string $uuid,
        ValidatorInterface $validator,
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException();
        }
        if ($request->getContent() === "") {
            throw new BadRequestHttpException();
        }
        $customer = $customerRepository->findOneBy(['uuid' => $uuid, 'reseller' => $this->getUser()]);
        if (!$customer) {
            throw new EntityNotFoundException();
        }
        /** @var Customer $updateCustomer */
        $updateCustomer = $serializer->deserialize(
            $request->getContent(),
            Customer::class,
            'json',
            [
                AbstractNormalizer::OBJECT_TO_POPULATE => $customer,
                DenormalizerInterface::COLLECT_DENORMALIZATION_ERRORS => true,
                'groups' => 'update:customer'
            ]
        );
        $errors = $validator->validate($updateCustomer);
        if ($errors->count() > 0) {
            $jsonErrors = [];
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $jsonErrors[$error->getPropertyPath()] = $translator->trans($error->getMessageTemplate());
            }
            throw new UnprocessableEntityHttpException('', null, 0, ['errors' => $jsonErrors]);
        }
        $managerRegistry->getManager()->flush();
        $key = sprintf("customer-%s", $uuid);

        $cache->delete($key);
        $cache->invalidateTags(['customersCache']);
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($customer, $serializer) {
                echo 'Le client a bien été mis à jour !' . PHP_EOL;
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
