<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityNotFoundException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CustomerDeleteController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     * @throws InvalidArgumentException
     */
    #[Route('/api/customers/{uuid}', name: 'app_delete_customer', methods: ['DELETE'])]
    public function __invoke(
        CustomerRepository $customerRepository,
        string $uuid,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException();
        }
        /** @var Customer $customer */
        $customer = $customerRepository->findOneBy(['uuid' => $uuid,'reseller' => $this->getUser()]);
        if ($customer == null) {
            throw new EntityNotFoundException();
        }
        apcu_delete(sprintf("customer-%s", $uuid));
        apcu_clear_cache();
//        $cache->delete(sprintf("customer-%s", $uuid));
//        $cache->invalidateTags(['customersCache']);
        $customerRepository->remove($customer, true);
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
