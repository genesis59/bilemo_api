<?php

namespace App\Versioning\Transformer;

class CustomerTransformer implements EntityTransformerInterface
{
    public function __construct(private readonly SmartphoneTransformer $smartphoneTransformer)
    {
    }

    /**
     * @param mixed[] $entity
     * @param string $methodName
     * @return void
     */
    public function transformFrom12To11(array &$entity, string $methodName): void
    {
        foreach ($entity['smartphones'] as &$smartphone) {
            $this->smartphoneTransformer->$methodName($smartphone, $methodName);
        }
    }

    /**
     * @param mixed[] $entity
     * @param string $methodName
     * @return void
     */
    public function transformFrom11To10(array &$entity, string $methodName): void
    {
        foreach ($entity['smartphones'] as &$smartphone) {
            $this->smartphoneTransformer->$methodName($smartphone, $methodName);
        }
    }
}
