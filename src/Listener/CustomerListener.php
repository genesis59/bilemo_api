<?php

namespace App\Listener;

use App\Entity\Customer;
use App\Entity\Reseller;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Uuid;

class CustomerListener
{
    public function __construct(
        private readonly Security $security
    ) {
    }
    public function prePersist(Customer $customer, LifecycleEventArgs $args): void
    {
        if ($this->security->getUser()) {
            /** @var Reseller $reseller */
            $reseller = $this->security->getUser();
            $customer->addReseller($reseller);
        }
        $customer->setCreatedAt(new \DateTimeImmutable());
        $customer->setUuid(Uuid::v4());
    }

    public function preUpdate(Customer $customer, LifecycleEventArgs $args): void
    {
        $customer->setUpdatedAt(new \DateTimeImmutable());
    }
}
