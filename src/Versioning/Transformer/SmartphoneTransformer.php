<?php

// Un transformer ne modifie pas les attributs de relation

namespace App\Versioning\Transformer;

class SmartphoneTransformer implements EntityTransformerInterface
{
    /**
     * @param mixed[] &$entity
     * @param string $methodName
     * @return void
     */
    public function transformFrom12To11(array &$entity, string $methodName): void
    {
        if (array_key_exists('price', $entity)) {
            $entity['price'] = sprintf('%s €', $entity['price']);
        }
    }
    /**
     * @param mixed[] &$entity
     * @param string $methodName
     * @return void
     */
    public function transformFrom11To10(array &$entity, string $methodName): void
    {
        unset($entity['price']);
    }
}
