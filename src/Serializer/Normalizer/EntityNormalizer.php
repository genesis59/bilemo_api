<?php

namespace App\Serializer\Normalizer;

use App\Entity\Customer;
use App\Entity\Smartphone;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class EntityNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    /**
     * @template T
     * @param T $object
     * @param array<string,mixed> $data
     * @return array|string[]
     */
    private function buildEntityLinks($object, array $data): array
    {
        $links = [];
        if (method_exists($object::class, 'getRoutes')) {
            $routes = $object->getRoutes();
            if (array_key_exists('self', $routes)) {
                $links['self'] = $this->urlGenerator->generate(
                    $routes['self'],
                    ["uuid" => $data["uuid"]]
                );
            }
            if (array_key_exists('create', $routes)) {
                $links['create'] = $this->urlGenerator->generate(
                    $routes['create']
                );
            }
            if (array_key_exists('update', $routes)) {
                $links['update'] = $this->urlGenerator->generate(
                    $routes['update'],
                    ["uuid" => $data["uuid"]]
                );
            }
            if (array_key_exists('delete', $routes)) {
                $links['delete'] = $this->urlGenerator->generate(
                    $routes['delete'],
                    ["uuid" => $data["uuid"]]
                );
            }
        }
        return $links;
    }

    public function __construct(
        #[Autowire(service: ObjectNormalizer::class)]
        private readonly NormalizerInterface $normalizer,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * @param $object
     * @param string|null $format
     * @param array<string,mixed> $context
     * @return array<int|string, mixed>
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array<string,mixed> $data */
        $data = $this->normalizer->normalize($object, $format, $context);
        if (isset($data['uuid'])) {
            $data = [
                "_links" => $this->buildEntityLinks($object, $data),
                ...$data
            ];
        }
        return $data;
    }

    /**
     * @param $data
     * @param string|null $format
     * @param array<string,mixed> $context
     * @return bool
     */
    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Customer || $data instanceof Smartphone;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
