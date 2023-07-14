<?php

namespace App\Controller\Smartphone;

use App\Entity\Smartphone;
use App\Repository\SmartphoneRepository;
use App\Versioning\ApiTransformer;
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

class SmartphoneGetOneController extends AbstractController
{
    /**
     * @throws EntityNotFoundException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    #[Route('/api/smartphones/{uuid}', name: 'app_get_smartphone', methods: ['GET'])]
    public function __invoke(
        SmartphoneRepository $smartphoneRepository,
        string $uuid,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer,
        ApiTransformer $smartphoneVersionManager
    ): JsonResponse {

        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException();
        }

        $key = sprintf("smartphone-%s", $uuid);
        $dataJson = $cache->get(
            $key,
            function (ItemInterface $item) use ($smartphoneRepository, $serializer, $uuid) {
                $item->expiresAfter(random_int(0, 300) + 3300);
                $smartphone = $smartphoneRepository->findOneBy(['uuid' => $uuid]);
                if (!$smartphone) {
                    throw new EntityNotFoundException();
                }
                $context = [
                    'groups' => 'read:smartphone',
                    'type' => Smartphone::class
                ];
                return $serializer->serialize($smartphone, 'json', $context);
            }
        );
        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
