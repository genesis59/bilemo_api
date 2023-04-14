<?php

namespace App\Listener;

use App\Entity\Reseller;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class ResellerListener
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }
    public function prePersist(Reseller $reseller, LifecycleEventArgs $args): void
    {
        $reseller->setCreatedAt(new \DateTimeImmutable());
        $reseller->setRoles(['ROLE_USER']);
        $reseller->setPassword($this->passwordHasher->hashPassword($reseller, $reseller->getPassword()));
        $reseller->setUuid(Uuid::v4());
    }
}
