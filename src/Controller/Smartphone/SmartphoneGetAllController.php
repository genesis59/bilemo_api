<?php

namespace App\Controller\Smartphone;

use App\Paginator\PaginatorService;
use App\Repository\SmartphoneRepository;
use Psr\Cache\InvalidArgumentException;
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
     * @throws InvalidArgumentException
     */
    #[Route('/api/smartphones', name: 'app_get_smartphones', methods: ['GET'])]
    public function __invoke(
        Request $request,
        SmartphoneRepository $smartphoneRepository,
        PaginatorService $paginatorService,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer
    ): JsonResponse {
        $paginatorService->create($smartphoneRepository, $request, 'app_get_smartphones');
        $key = sprintf(
            "smartphones-%s-%s-%s",
            $paginatorService->getCurrentPage(),
            $paginatorService->getLimit(),
            $paginatorService->getSearch()
        );
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($paginatorService, $smartphoneRepository, $serializer) {
                $item->tag('smartphonesCache');
                $item->expiresAfter(random_int(0, 300) + 3300);
                return $serializer->serialize($paginatorService, 'json', [
                    'groups' => 'read:smartphone',
                    'repository' => $smartphoneRepository
                ]);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
