<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerGetOneController extends AbstractController
{
    #[Route('/api/customers/{id}', name: 'app_get_customer')]
    public function index(Customer $customer): JsonResponse
    {
        return $this->json($customer, Response::HTTP_OK, [], ['groups' => 'read:customer']);
    }
}
