<?php

namespace App\Versioning;

use App\Entity\Customer;
use App\Entity\Smartphone;
use App\Versioning\Transformer\CustomerTransformer;
use App\Versioning\Transformer\SmartphoneTransformer;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiTransformer
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly SmartphoneTransformer $smartphoneTransformer,
        private readonly CustomerTransformer $customerTransformer,
        private readonly TranslatorInterface $translator
    ) {
    }

    public function transform(string $data, string $version): string
    {
        if ($this->parameterBag->get('last_api_version') === $version) {
            return $data;
        }

        if (is_array($this->parameterBag->get('api_versions'))) {
            $versionsList = array_reverse($this->parameterBag->get('api_versions'));
            // Vérification de la véracité de la version
            if (!array_key_exists($version, $versionsList)) {
                throw new BadRequestHttpException($this->translator->trans('app.exception.bad_version'));
            }
            // Résolution des versions antérieures
            foreach ($versionsList as $key => $methodName) {
                $data = $this->transformData($data, $methodName);
                if ($key === $version) {
                    break;
                }
            }
        }
        return $data;
    }

    public function transformData(string $content, string $methodName): string
    {
        $content = json_decode($content, true);
        if (array_key_exists('items', $content)) {
            // transformation des entités avec pagination
            foreach ($content['items'] as &$item) {
                $this->transformAssociativeEntity($item, $methodName);
            }
        }
        // transformation des entités sans pagination
        $this->transformAssociativeEntity($content, $methodName);
        if (!is_string(json_encode($content))) {
            throw new UnexpectedValueException();
        }
        return json_encode($content);
    }

    /**
     * @param mixed[] $entity
     * @param string $methodName
     * @return void
     */
    private function transformAssociativeEntity(array &$entity, string $methodName): void
    {
        if (array_key_exists("_type", $entity)) {
            if ($entity["_type"] === Customer::class) {
                $this->customerTransformer->$methodName($entity, $methodName);
            }
            if ($entity["_type"] === Smartphone::class) {
                $this->smartphoneTransformer->$methodName($entity, $methodName);
            }
        }
    }
}
