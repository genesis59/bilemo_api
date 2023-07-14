<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CustomerGetOneController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[Route('/api/customers/{uuid}', name: 'app_get_customer', methods: ['GET'])]
    public function __invoke(
        string $uuid,
        CustomerRepository $customerRepository,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer
    ): JsonResponse {
        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException();
        }

        $key = sprintf("customer-%s", $uuid);

        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($customerRepository, $serializer, $uuid) {
                $customer = $customerRepository->findOneBy(['uuid' => $uuid, 'reseller' => $this->getUser()]);
                if (!$customer) {
                    throw new EntityNotFoundException();
                }
                $item->expiresAfter(random_int(0, 300) + 3300);

                return $serializer->serialize($customer, 'json', [
                    'groups' => 'read:customer',
                    'type' => Customer::class
                ]);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
