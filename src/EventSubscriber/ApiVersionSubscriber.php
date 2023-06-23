<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiVersionSubscriber implements EventSubscriberInterface
{
    /**
     * @var string[]
     */
    private array $versioningRoute = [
        "app_get_smartphones",
        "app_get_smartphones"
    ];
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (in_array($event->getRequest()->get("_route"), $this->versioningRoute)) {
            $acceptHeader = $event->getRequest()->headers->get('Accept');
            $isValidVersion = false;
            if (count(explode(";", $acceptHeader)) > 1) {
                $argVersion = explode(";", $acceptHeader)[1];
                if (count(explode("=", $argVersion)) > 1 && explode("=", $argVersion)[0] === "version") {
                    $version = explode("=", $argVersion)[1];
                    if ($version === "1.0") {
                        $event->getRequest()->headers->set("groups", "read:smartphone_v1.0");
                        $isValidVersion = true;
                    }
                    if ($version === "1.1") {
                        $event->getRequest()->headers->set("groups", "read:smartphone_v1.1");
                        $isValidVersion = true;
                    }
                    if ($version === "1.2") {
                        $event->getRequest()->headers->set("groups", "read:smartphone_vMax");
                        $isValidVersion = true;
                    }
                }
            }
            if (!$isValidVersion) {
                throw new BadRequestHttpException($this->translator->trans('app.exception.bad_version'));
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
