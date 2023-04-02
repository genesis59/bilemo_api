<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerUpdateController extends AbstractController
{
    #[Route('/api/customers/{id}', name: 'app_update_customer', methods: ['PUT'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        CustomerRepository $customerRepository,
        Customer $customer
    ): JsonResponse {

        /** @var Customer $updateCustomer */
        $updateCustomer = $serializer->deserialize(
            $request->getContent(),
            Customer::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $customer]
        );
        $updateCustomer->setUpdatedAt(new \DateTimeImmutable());
        return $this->json($updateCustomer, Response::HTTP_OK, [], ['groups' => 'read:customer']);
    }
}
