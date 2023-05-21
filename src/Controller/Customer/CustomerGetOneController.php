<?php

namespace App\Controller\Customer;

use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class CustomerGetOneController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     */
    #[Route('/api/customers/{uuid}', name: 'app_get_customer', methods: ['GET'])]
    public function __invoke(string $uuid, CustomerRepository $customerRepository): JsonResponse
    {
        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException();
        }
        $customer = $customerRepository->findOneBy(['uuid' => $uuid, 'reseller' => $this->getUser()]);
        if (!$customer) {
            throw new EntityNotFoundException();
        }
        return $this->json($customer, Response::HTTP_OK, [], [
            'groups' => 'read:customer',
            'links' => [
                "self" => 'app_get_customer',
                "create" => 'app_create_customer',
                "update" => 'app_update_customer',
                "delete" => 'app_delete_customer'
            ]
        ]);
    }
}
