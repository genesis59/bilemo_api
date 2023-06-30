<?php

namespace App\EventSubscriber;

use App\Versioning\ApiVersionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiVersionSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly ApiVersionManager $apiVersionManager)
    {
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $apiVersion = $event->getRequest()->headers->get('x-api-key');
        if (!$apiVersion) {
            return;
        }
        if ($event->getResponse() instanceof JsonResponse) {
            if (!is_string($event->getResponse()->getContent())) {
                throw new UnexpectedValueException();
            }
            $content = $this->apiVersionManager->getVersion($event->getResponse()->getContent(), $apiVersion);
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
