<?php

namespace App\VersionManager;

use App\Entity\Smartphone;

class SmartphoneVersionManager
{
    /**
     * @param Smartphone $smartphone
     * @param array<string,string> $context
     * @return void
     */
    public function updateSmartphoneVersion(Smartphone $smartphone, array $context): void
    {
        if ($context['groups'] === "read:smartphone_v1.1") {
            $smartphone->setPrice($smartphone->getPriceEuro());
        }
    }
}
