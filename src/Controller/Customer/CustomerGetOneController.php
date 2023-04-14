<?php

namespace App\Controller\Customer;

use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerGetOneController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     */
    #[Route('/api/customers/{idCustomer}', name: 'app_get_customer', methods: ['GET'])]
    public function __invoke(string $idCustomer, CustomerRepository $customerRepository): JsonResponse
    {
        $customer = $customerRepository->find($idCustomer);
        if (!$customer) {
            throw new EntityNotFoundException();
        }
        return $this->json($customer, Response::HTTP_OK, [], ['groups' => 'read:customer']);
    }
}
