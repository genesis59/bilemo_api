<?php

namespace App\Controller\Smartphone;

use App\Entity\Smartphone;
use App\Paginator\PaginatorService;
use App\Repository\SmartphoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SmartphoneGetAllController extends AbstractController
{
    #[Route('/api/smartphones', name: 'app_get_smartphones', methods: ['GET'])]
    public function __invoke(
        Request $request,
        SmartphoneRepository $smartphoneRepository,
        PaginatorService $paginatorService,
        TranslatorInterface $translator
    ): JsonResponse {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', $this->getParameter('default_smartphone_per_page'));
        $q = $request->get('q');
        $smartphones = $paginatorService->paginate(Smartphone::class, $page, $limit, $q);
        if (count($smartphones) === 0) {
            throw new NotFoundHttpException(
                $translator->trans('app.exception.not_found_http_exception_page'),
                null,
                0,
                ['page' => true]
            );
        }
        return $this->json($smartphones, Response::HTTP_OK, [], ['groups' => 'read:smartphone']);
    }
}
