<?php

namespace App\Controller\Customer;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerGetAllController extends AbstractController
{
    #[Route('/api/customers', name: 'app_get_customers')]
    public function index(CustomerRepository $customerRepository): JsonResponse
    {
        $customers = $customerRepository->findAll();
        return $this->json($customers, Response::HTTP_OK, [], ['groups' => 'read:customer']);
    }
}
