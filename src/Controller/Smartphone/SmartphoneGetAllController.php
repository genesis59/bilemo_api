<?php

namespace App\Controller\Smartphone;

use App\Paginator\PaginatorService;
use App\Repository\SmartphoneRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class SmartphoneGetAllController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/api/smartphones', name: 'app_get_smartphones', methods: ['GET'])]
    public function __invoke(
        Request $request,
        SmartphoneRepository $smartphoneRepository,
        PaginatorService $paginatorService,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer
    ): JsonResponse {
        $key = sprintf(
            "smartphones-%s-%s-%s-%s",
            intval($request->get('page', 1)),
            intval($request->get('limit', $this->getParameter('default_customer_per_page'))),
            $request->get('q', ""),
            $request->headers->get('groups', 'read:smartphone_vMax')
        );
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($paginatorService, $smartphoneRepository, $serializer, $request) {
                $item->tag('smartphonesCache');
                $item->expiresAfter(random_int(0, 300) + 3300);
                $context = [
                    'groups' => $request->headers->get('groups', 'read:smartphone_vMax')
                ];
                $paginatorService->create($smartphoneRepository, $request, 'app_get_smartphones');
                return $serializer->serialize($paginatorService, 'json', $context);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
