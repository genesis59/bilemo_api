<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
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
        /** check that the content is what is expected  */
        if ($event->getRequest()->getMethod() === "POST" || $event->getRequest()->getMethod() === "PUT") {
            if ($event->getRequest()->getContentTypeFormat() !== 'json') {
                $event->setResponse(new JsonResponse(
                    $this->serializer->serialize([
                        'message' => $this->translator->trans('app.exception.bad_content_type')
                    ], 'json'),
                    Response::HTTP_BAD_REQUEST,
                    [],
                    true
                ));
                return;
            }
        }

        $exception = $event->getThrowable();
        /** The request hasn't been validated by the validator */
        if ($exception instanceof UnprocessableEntityHttpException) {
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize($exception->getHeaders()['errors'], 'json'),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                [],
                true
            ));
        }

        /** The request doesn't have the expected content */
        if ($exception instanceof BadRequestHttpException) {
            $message = $exception->getMessage();
            if ($event->getRequest()->get('_route') === "api_login_check") {
                if ($exception->getMessage() === "The key \"password\" must be provided.") {
                    $message = $this->translator->trans('app.exception.bad_request_login_miss_password');
                }
                if ($exception->getMessage() === "The key \"username\" must be provided.") {
                    $message = $this->translator->trans('app.exception.bad_request_login_miss_username');
                }
            }
            if ($event->getRequest()->getContent() === "") {
                $message = $this->translator->trans('app.exception.bad_request_http_exception');
            }
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize([
                    'message' => $message
                ], 'json'),
                Response::HTTP_BAD_REQUEST,
                [],
                true
            ));
        }

        /** Route not found or content is empty during the paging process */
        if ($exception instanceof NotFoundHttpException) {
            $message = $this->translator->trans('app.exception.not_found_http_exception');
            if (array_key_exists('page', $exception->getHeaders())) {
                $message = $this->translator->trans('app.exception.not_found_http_exception_page');
            }
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize([
                    'message' => $message
                ], 'json'),
                Response::HTTP_NOT_FOUND,
                [],
                true
            ));
        }

        /** Entity doesn't exist */
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

        /** untreated exception  */
//        if (!$event->getResponse()) {
//            $event->setResponse(new JsonResponse(
//                $this->serializer->serialize([
//                    'message' => $this->translator->trans('app.exception.internal_server_error')
//                ], 'json'),
//                Response::HTTP_INTERNAL_SERVER_ERROR,
//                [],
//                true
//            ));
//        }
    }
}
