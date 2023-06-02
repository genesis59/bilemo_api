<?php

namespace App\Serializer\Normalizer;

use App\Entity\Customer;
use App\Service\EntityRouteGenerator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PaginationNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)]
        private readonly NormalizerInterface $normalizer,
        private readonly EntityRouteGenerator $entityRouteGenerator
    ) {
    }

    /**
     * @param $object
     * @param string|null $format
     * @param array<string,mixed> $context
     * @return array<string,mixed>
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        $items = [];
        foreach ($object as $item) {
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
            '_pagination' => $context['pagination'],
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
        return is_array($data) && isset($context['pagination']);
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
