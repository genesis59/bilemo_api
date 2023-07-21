<?php

namespace App\Versioning\Transformer;

class ApiTransformer11To10 implements ApiTransformerInterface
{
    /**
     * @param mixed[] &$entity
     * @return void
     */
    public function transformSmartphone(array &$entity): void
    {
        unset($entity['price']);
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
