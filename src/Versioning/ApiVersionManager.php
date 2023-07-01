<?php

namespace App\Versioning;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class ApiVersionManager
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    public function getVersion(string $content, string $version): string
    {
        if ($this->parameterBag->get('api_version_max') === $version) {
            return $content;
        }
        if (is_array($this->parameterBag->get('api_versions'))) {
            $versionsList = array_reverse($this->parameterBag->get('api_versions'));
            if (!array_key_exists($version, $versionsList)) {
                throw new BadRequestHttpException();
            }

            foreach ($versionsList as $key => $className) {
                /** @var ApiConverterInterface $apiConverter */
                $apiConverter = new $className();
                $content = $apiConverter->convert($content);
                if ($key === $version) {
                    break;
                }
            }
        }
        return $content;
    }
}
