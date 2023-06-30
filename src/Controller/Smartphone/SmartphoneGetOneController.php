<?php

namespace App\Controller\Smartphone;

use App\Repository\SmartphoneRepository;
use App\Versioning\ApiVersionManager;
use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class SmartphoneGetOneController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[Route('/api/smartphones/{uuid}', name: 'app_get_smartphone', methods: ['GET'])]
    public function __invoke(
        Request $request,
        SmartphoneRepository $smartphoneRepository,
        string $uuid,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer,
        ApiVersionManager $smartphoneVersionManager
    ): JsonResponse {

        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException();
        }

        $key = sprintf("smartphone-%s-%s", $uuid, $request->headers->get('groups', 'read:smartphone_vMax'));
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use (
                $request,
                $smartphoneRepository,
                $serializer,
                $uuid
            ) {
                $item->expiresAfter(random_int(0, 300) + 3300);
                $smartphone = $smartphoneRepository->findOneBy(['uuid' => $uuid]);
                if (!$smartphone) {
                    throw new EntityNotFoundException();
                }
                $context = [
                    'groups' => $request->headers->get('groups', 'read:smartphone_vMax')
                ];
                return $serializer->serialize($smartphone, 'json', $context);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
