<?php

namespace App\Controller\Customer;

use App\Paginator\PaginatorService;
use App\Repository\CustomerRepository;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class CustomerGetAllController extends AbstractController
{
    /**
     * @throws InvalidArgumentException
     */
    #[Route('/api/customers', name: 'app_get_customers', methods: ['GET'])]
    public function __invoke(
        Request $request,
        CustomerRepository $customerRepository,
        PaginatorService $paginatorService,
        SerializerInterface $serializer,
        TagAwareCacheInterface $cache
    ): JsonResponse {
        $paginatorService->create($customerRepository, $request, 'app_get_customers');
        $key = sprintf(
            "customers-%s-%s-%s",
            $paginatorService->getCurrentPage(),
            $paginatorService->getLimit(),
            $paginatorService->getSearch()
        );
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($paginatorService, $customerRepository, $serializer) {
                $item->expiresAfter(300);
                $item->tag('customersCache');
                return $serializer->serialize($paginatorService, 'json', [
                    'groups' => 'read:customer',
                    'repository' => $customerRepository,
                ]);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
