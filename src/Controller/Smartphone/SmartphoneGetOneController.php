<?php

namespace App\Controller\Smartphone;

use App\Repository\SmartphoneRepository;
use Doctrine\ORM\EntityNotFoundException;
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
     * @throws InvalidArgumentException
     */
    #[Route('/api/smartphones/{uuid}', name: 'app_get_smartphone', methods: ['GET'])]
    public function __invoke(
        SmartphoneRepository $smartphoneRepository,
        string $uuid,
        TagAwareCacheInterface $cache,
        SerializerInterface $serializer
    ): JsonResponse {

        if (!Uuid::isValid($uuid)) {
            throw new EntityNotFoundException();
        }

        $key = sprintf("smartphone-%s", $uuid);

        $data = apcu_fetch($key);
        if (!$data) {
            echo 'mise en cache';
            $smartphone = $smartphoneRepository->findOneBy(['uuid' => $uuid]);
            if (!$smartphone) {
                throw new EntityNotFoundException();
            }

            $data = $serializer->serialize($smartphone, 'json', [
                'groups' => 'read:smartphone'
            ]);
            apcu_add($key, $data);
        }
        return new JsonResponse($data, Response::HTTP_OK, [], true);
//        $dataJson = $cache->get(
//            $key,
//            function (ItemInterface $item) use ($smartphoneRepository, $serializer, $uuid) {
//                $item->expiresAfter(random_int(0, 300) + 3300);
//                $smartphone = $smartphoneRepository->findOneBy(['uuid' => $uuid]);
//                if (!$smartphone) {
//                    throw new EntityNotFoundException();
//                }
//                return $serializer->serialize($smartphone, 'json', [
//                    'groups' => 'read:smartphone'
//                ]);
//            }
//        );
//        return new JsonResponse($dataJson, Response::HTTP_OK, [], true);
    }
}
