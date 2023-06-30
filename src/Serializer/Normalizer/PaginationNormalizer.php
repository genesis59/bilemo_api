<?php

namespace App\Serializer\Normalizer;

use App\Entity\Smartphone;
use App\Paginator\PaginatorService;
use App\Service\EntityRouteGenerator;
use App\Versioning\ApiVersionManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PaginationNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param PaginatorService $paginatorService
     * @return array<string,mixed>
     */
    private function createPagination(
        PaginatorService $paginatorService
    ): array {
        return [
            "current_page_number" => $paginatorService->getCurrentPage(),
            "number_items_per_page" => $paginatorService->getLimit(),
            "total_items_count" => $paginatorService->getCountItemsTotal(),
            "first_page_link" => $paginatorService->getUrlFirstPage(),
            "last_page_link" => $paginatorService->getUrlLastPage(),
            "previous_page_link" => $paginatorService->getUrlPreviousPage(),
            "next_page_link" => $paginatorService->getUrlNextPage()
        ];
    }

    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)]
        private readonly NormalizerInterface $normalizer,
        private readonly EntityRouteGenerator $entityRouteGenerator
    ) {
    }

    /**
     * @param PaginatorService $object
     * @param string|null $format
     * @param array<string,mixed> $context
     * @return array<string,mixed>
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $items = [];
        foreach ($object->getData() as $item) {
            /** @var array<string,mixed> $itemNormalized */
            $itemNormalized = $this->normalizer->normalize($item, null, [
                'groups' => $context['groups']
            ]);
            $itemTransformed = [
                "_links" => $this->entityRouteGenerator->getAllEntityRoutesList($item),
                ...$itemNormalized
            ];
            $items[] = $itemTransformed;
        }
        return [
            '_pagination' => $this->createPagination($object),
            'items' => $items
        ];
    }

    /**
     * @param $data
     * @param string|null $format
     * @param array<string,mixed> $context
     * @return bool
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof PaginatorService;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
