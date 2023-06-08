<?php

namespace App\Serializer\Normalizer;

use App\Paginator\PaginatorService;
use App\Repository\CustomerRepository;
use App\Repository\SmartphoneRepository;
use App\Service\EntityRouteGenerator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Validator\Exception\BadMethodCallException;
use Symfony\Contracts\Translation\TranslatorInterface;

class PaginationNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @param PaginatorService $paginatorService
     * @param array<string,mixed> $context
     * @return array<string,mixed>
     */
    private function createPagination(
        PaginatorService $paginatorService,
        array $context
    ): array {
        $repository = $context['repository'];
        if (
            (!$repository instanceof SmartphoneRepository && !$repository instanceof CustomerRepository) ||
            !method_exists($repository, "searchAndPaginate")
        ) {
            throw new BadMethodCallException(
                $this->translator->trans('app.exception.bad_method_call_exception_searchAndPaginate')
            );
        }
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
        private readonly EntityRouteGenerator $entityRouteGenerator,
        private readonly TranslatorInterface $translator
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
            '_pagination' => $this->createPagination($object, $context),
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
        return $data instanceof PaginatorService && (
            $context['repository'] instanceof CustomerRepository ||
            $context['repository'] instanceof SmartphoneRepository
            );
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
