<?php

namespace App\Versioning;

use App\Entity\Customer;
use App\Entity\Smartphone;
use App\Versioning\Transformer\ApiTransformerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiTransformer
{
    /**
     * Gère la transformation des entités en cas de pagination ou non
     */
    private function transformData(string $content, ApiTransformerInterface $transformer): string
    {
        $content = json_decode($content, true);
        if (array_key_exists('items', $content)) {
            // transformation des entités avec pagination
            foreach ($content['items'] as &$item) {
                $this->transformAssociativeEntity($item, $transformer);
            }
        }
        // transformation des entités sans pagination
        $this->transformAssociativeEntity($content, $transformer);
        if (!is_string(json_encode($content))) {
            throw new UnexpectedValueException();
        }
        return json_encode($content);
    }

    /**
     * Récupère l'information _type laissée par le contrôleur et transforme l'entité correspondante
     * @param mixed[] $entity
     * @param ApiTransformerInterface $transformer
     * @return void
     */
    private function transformAssociativeEntity(array &$entity, ApiTransformerInterface $transformer): void
    {
        if (array_key_exists("_type", $entity)) {
            if ($entity["_type"] === Customer::class) {
                $transformer->transformCustomer($entity);
            }
            if ($entity["_type"] === Smartphone::class) {
                $transformer->transformSmartphone($entity);
            }
        }
    }
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * Transforme les données en fonction de la version demandée
     */
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
            foreach ($versionsList as $key => $className) {
                if (!class_exists($className)) {
                    throw new BadRequestHttpException($this->translator->trans('app.exception.no_transformer'));
                }
                $transformer = new $className();
                if (!$transformer instanceof ApiTransformerInterface) {
                    throw new BadRequestHttpException($this->translator->trans('app.exception.no_transformer'));
                }
                $data = $this->transformData($data, $transformer);
                if ($key === $version) {
                    break;
                }
            }
        }
        return $data;
    }
}
