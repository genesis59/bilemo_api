<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

class CustomerUpdateController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     * @throws EntityNotFoundException
     */
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
                    'groups' => 'read:customer'
                ]);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
