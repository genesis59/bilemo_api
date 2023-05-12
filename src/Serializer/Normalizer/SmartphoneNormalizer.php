<?php

namespace App\Serializer\Normalizer;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SmartphoneNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    public function __construct(
        private readonly ObjectNormalizer $normalizer,
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
                "_links" => [
                    "self" => $this->urlGenerator->generate(
                        'app_get_smartphone',
                        ["uuid" => $data["uuid"]]
                    )
                ],
                ... $data
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
        return $data instanceof \App\Entity\Smartphone;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
