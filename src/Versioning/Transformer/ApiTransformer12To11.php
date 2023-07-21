<?php

namespace App\Versioning\Transformer;

class ApiTransformer12To11 implements ApiTransformerInterface
{
    /**
     * @param mixed[] &$entity
     * @return void
     */
    public function transformSmartphone(array &$entity): void
    {
        if (array_key_exists('price', $entity)) {
            $entity['price'] = sprintf('%s €', $entity['price']);
        }
    }

    /**
     * @param mixed[] $entity
     * @return void
     */
    public function transformCustomer(array &$entity): void
    {
        foreach ($entity['smartphones'] as &$smartphone) {
            $this->transformSmartphone($smartphone);
        }
    }
}
