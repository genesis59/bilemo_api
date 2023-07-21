<?php

namespace App\EventSubscriber;

use App\Versioning\ApiTransformer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

class ApiVersionSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly ApiTransformer $apiTransformer)
    {
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $apiVersion = $event->getRequest()->headers->get('api-version');
        if (!$apiVersion) {
            return;
        }
        if ($event->getResponse() instanceof JsonResponse) {
            if (!is_string($event->getResponse()->getContent())) {
                throw new UnexpectedValueException();
            }
            $content = $this->apiTransformer->transform($event->getResponse()->getContent(), $apiVersion);
            $event->getResponse()->setContent($content);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
