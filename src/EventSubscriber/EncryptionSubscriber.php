<?php

namespace App\EventSubscriber;

use App\Attribute\SensibleData;
use App\Encryption\EncryptionService;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use ReflectionClass;
use ReflectionException;
use SodiumException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class EncryptionSubscriber implements EventSubscriberInterface
{
    /**
     * @throws SodiumException
     * @throws ReflectionException
     */
    private function cryptographicTransform(object $entity, string $action): void
    {
        $reflectionClass = new ReflectionClass($entity::class);
        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->getAttributes(SensibleData::class)) {
                $getter = sprintf('get%s', ucfirst($property->getName()));
                $setter = sprintf('set%s', ucfirst($property->getName()));
                $result = $this->encryptionService->$action($entity->$getter());
                $entity->$setter($result);
            }
        }
    }
    public function __construct(
        private readonly EncryptionService $encryptionService,
        private readonly ParameterBagInterface $parameterBag
    ) {
    }

    /**
     * @throws SodiumException
     * @throws ReflectionException
     */
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        if ($entity instanceof Customer) {
            $this->cryptographicTransform($entity, EncryptionService::ENCRYPT_METHOD_NAME);
        }
    }

    /**
     * @throws SodiumException
     * @throws ReflectionException
     */
    public function preUpdate(LifecycleEventArgs $args): void
    {
        if (!$this->parameterBag->get('fixtures_loading')) {
            $entity = $args->getObject();
            if ($entity instanceof Customer) {
                $this->cryptographicTransform($entity, EncryptionService::ENCRYPT_METHOD_NAME);
            }
        }
    }

    /**
     * @throws SodiumException
     * @throws ReflectionException
     */
    public function postLoad(LifecycleEventArgs $args): void
    {
        if (!$this->parameterBag->get('fixtures_loading')) {
            $entity = $args->getObject();
            if ($entity instanceof Customer) {
                $this->cryptographicTransform($entity, EncryptionService::DECRYPT_METHOD_NAME);
            }
        }
    }

    /**
     * @throws SodiumException
     * @throws ReflectionException
     */
    public function postPersist(LifecycleEventArgs $args): void
    {
        if (!$this->parameterBag->get('fixtures_loading')) {
            $entity = $args->getObject();
            if ($entity instanceof Customer) {
                $this->cryptographicTransform($entity, EncryptionService::DECRYPT_METHOD_NAME);
            }
        }
    }

    /**
     * @throws SodiumException
     * @throws ReflectionException
     */
    public function postUpdate(LifecycleEventArgs $args): void
    {
        if (!$this->parameterBag->get('fixtures_loading')) {
            $entity = $args->getObject();
            if ($entity instanceof Customer) {
                $this->cryptographicTransform($entity, EncryptionService::DECRYPT_METHOD_NAME);
            }
        }
    }
    public function getSubscribedEvents(): array
    {
        return [
            Events::postLoad => 'postLoad',
            Events::prePersist => 'prePersist',
            Events::preUpdate => 'preUpdate',
            Events::postPersist => 'postPersist',
            Events::postUpdate => 'postUpdate',
        ];
    }
}
