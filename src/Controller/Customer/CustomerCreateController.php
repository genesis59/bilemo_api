<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

class CustomerCreateController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     */
    #[Route('/api/customers', name: 'app_create_customer', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        CustomerRepository $customerRepository,
        ValidatorInterface $validator,
        TranslatorInterface $translator,
        TagAwareCacheInterface $cache
    ): JsonResponse {

        if ($request->getContent() === "") {
            throw new BadRequestHttpException();
        }
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
                $jsonErrors[$error->getPropertyPath()] = $translator->trans($error->getMessageTemplate());
            }

            throw new UnprocessableEntityHttpException('My custom error message', null, 0, ['errors' => $jsonErrors]);
        }
        $customerRepository->save($customer, true);

        $key = sprintf("customer-%s", $customer->getUuid());
        $cache->invalidateTags(['customersCache']);
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($customer, $serializer) {
                $item->expiresAfter(random_int(0, 300) + 3300);
                echo 'Le client a bien été créé !' . PHP_EOL;
                return $serializer->serialize($customer, 'json', [
                    'groups' => 'read:customer'
                ]);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_CREATED, [], true);
    }
}
