<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class CustomerUpdateController extends AbstractController
{
    #[Route('/api/customers/{idCustomer}', name: 'app_update_customer', methods: ['PUT'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        CustomerRepository $customerRepository,
        string $idCustomer,
        ValidatorInterface $validator,
        TranslatorInterface $translator
    ): JsonResponse {

        $customer = $customerRepository->find($idCustomer);
        if (!$customer) {
            throw new EntityNotFoundException();
        }
        if ($request->getContent() === "") {
            throw new NotEncodableValueException();
        }

        /** @var Customer $updateCustomer */
        $updateCustomer = $serializer->deserialize(
            $request->getContent(),
            Customer::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $customer]
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
        $updateCustomer->setUpdatedAt(new \DateTimeImmutable());
        return $this->json($updateCustomer, Response::HTTP_OK, [], ['groups' => 'read:customer']);
    }
}
