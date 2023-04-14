<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly TranslatorInterface $translator
    ) {
    }

    public static function getSubscribedEvents(): array
    {
// return the subscribed events, their methods and priorities
        return [
            KernelEvents::EXCEPTION => [
                ['processException'],
            ],
        ];
    }

    public function processException(ExceptionEvent $event): void
    {

        $exception = $event->getThrowable();
        if ($exception instanceof UnprocessableEntityHttpException) {
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize($exception->getHeaders()['errors'], 'json'),
                Response::HTTP_BAD_REQUEST,
                [],
                true
            ));
        }

        if ($exception instanceof NotEncodableValueException) {
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize([
                    'message' => $this->translator->trans('app.exception.not_encodable_value_exception')
                ], 'json'),
                Response::HTTP_BAD_REQUEST,
                [],
                true
            ));
        }

        if ($exception instanceof NotFoundHttpException) {
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize([
                    'message' => $this->translator->trans('app.exception.not_found_http_exception')
                ], 'json'),
                Response::HTTP_NOT_FOUND,
                [],
                true
            ));
        }

        if ($exception instanceof EntityNotFoundException) {
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize([
                    'message' => $this->translator->trans('app.exception.entity_not_found_exception')
                ], 'json'),
                Response::HTTP_NOT_FOUND,
                [],
                true
            ));
        }
        // Si une exception n'a pas encore été traitée
        if (!$event->getResponse()) {
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize([
                    'message' => $this->translator->trans('app.exception.internal_server_error')
                ], 'json'),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                [],
                true
            ));
        }
    }
}
