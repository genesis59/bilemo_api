<?php

namespace App\Controller\Customer;

use App\Entity\Customer;
use App\Paginator\PaginatorService;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CustomerGetAllController extends AbstractController
{
    #[Route('/api/customers', name: 'app_get_customers', methods: ['GET'])]
    public function __invoke(
        Request $request,
        CustomerRepository $customerRepository,
        PaginatorService $paginatorService,
        TranslatorInterface $translator
    ): JsonResponse {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', $this->getParameter('default_customer_per_page'));
        $customers = $paginatorService->paginate(Customer::class, $page, $limit);
        if (count($customers) === 0) {
            throw new NotFoundHttpException(
                $translator->trans('app.exception.not_found_http_exception_page'),
                null,
                0,
                ['page' => true]
            );
        }
        return $this->json($customers, Response::HTTP_OK, [], ['groups' => 'read:customer']);
    }
}
