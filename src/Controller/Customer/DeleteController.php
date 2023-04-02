<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    #[Route('/api/customers/{id}', name: 'app_delete_customer', methods: ['DELETE'])]
    public function __invoke(CustomerRepository $customerRepository, Customer $customer): JsonResponse
    {
        $customerRepository->remove($customer, true);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
