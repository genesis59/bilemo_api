<?php

namespace App\Controller\Customer;

use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class CustomerDeleteController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     */
    #[Route('/api/customers/{uuid}', name: 'app_delete_customer', methods: ['DELETE'])]
    public function __invoke(CustomerRepository $customerRepository, string $uuid): JsonResponse
    {
        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException();
        }
        $customer = $customerRepository->findOneBy(['uuid' => $uuid]);
        if (!$customer) {
            throw new EntityNotFoundException();
        }
        $customerRepository->remove($customer, true);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
