<?php

namespace App\Versioning\Transformer;

interface ApiTransformerInterface
{
    /**
     * @param mixed[] &$entity
     * @return void
     */
    public function transformSmartphone(array &$entity): void;

    /**
     * @param mixed[] $entity
     * @return void
     */
    public function transformCustomer(array &$entity): void;
}
