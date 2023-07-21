<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Paginator\PaginatorService;
use App\Repository\CustomerRepository;
use Exception;
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
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[Route('/api/customers', name: 'app_get_customers', methods: ['GET'])]
    public function __invoke(
        Request $request,
        CustomerRepository $customerRepository,
        PaginatorService $paginatorService,
        SerializerInterface $serializer,
        TagAwareCacheInterface $cache,
    ): JsonResponse {

        $key = sprintf(
            "customers-%s-%s-%s",
            intval($request->get('page', 1)),
            intval($request->get('limit', $this->getParameter('default_customer_per_page'))),
            $request->get('q', "")
        );

        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($paginatorService, $customerRepository, $serializer, $request) {
                echo "cache new";
                $paginatorService->create($customerRepository, $request, 'app_get_customers');
                $item->expiresAfter(random_int(0, 300) + 3300);
                $item->tag('customersCache');
                return $serializer->serialize($paginatorService, 'json', [
                    'groups' => 'read:customer',
                    'type' => Customer::class
                ]);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
