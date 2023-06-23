<?php

namespace App\Serializer\Normalizer;

use App\Entity\Customer;
use App\Entity\Reseller;
use App\Entity\Smartphone;
use App\Service\EntityRouteGenerator;
use App\VersionManager\SmartphoneVersionManager;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EntityNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
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
     * @return array<int|string, mixed>
     * @throws ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array<string,mixed> $data */
        $data = $this->normalizer->normalize($object, $format, $context);
        return [
            "_links" => $this->entityRouteGenerator->getAllEntityRoutesList($object),
            ...$data
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
        return $data instanceof Customer || $data instanceof Smartphone || $data instanceof Reseller;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return false;
    }
}
