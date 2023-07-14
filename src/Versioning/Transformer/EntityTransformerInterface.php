<?php

namespace App\Versioning\Transformer;

interface EntityTransformerInterface
{
    /**
     * @param mixed[] $entity
     * @param string $methodName
     * @return void
     */
    public function transformFrom12To11(array &$entity, string $methodName): void;

    /**
     * @param mixed[] $entity
     * @param string $methodName
     * @return void
     */
    public function transformFrom11To10(array &$entity, string $methodName): void;
}
