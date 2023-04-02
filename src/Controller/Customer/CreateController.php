<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Entity\Reseller;
use App\Repository\CustomerRepository;
use App\Repository\ResellerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class CreateController extends AbstractController
{
    #[Route('/api/customers', name: 'app_create_customer', methods: ['POST'])]
    public function __invoke(
        Request $request,
        SerializerInterface $serializer,
        ResellerRepository $resellerRepository,
        CustomerRepository $customerRepository
    ): JsonResponse {
        /** @var Reseller $reseller */
        $reseller = $resellerRepository->find(1);
        /** @var Customer $customer */
        $customer = $serializer->deserialize($request->getContent(), Customer::class, 'json');
        $customer->addReseller($reseller);
        $customer->setCreatedAt(new \DateTimeImmutable());
        $customer->setUuid(Uuid::v4());
        $customerRepository->save($customer, true);

        return $this->json($customer, Response::HTTP_CREATED, [], ['groups' => 'read:customer']);
    }
}
