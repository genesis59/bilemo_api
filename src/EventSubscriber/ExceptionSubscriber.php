<?php

namespace App\EventSubscriber;

use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\BadMethodCallException;
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

        if ($exception instanceof HttpException) {
            dump($exception);
            $message = $exception->getMessage();
            if (
                !$event->getRequest()->getContent() &&
                ($event->getRequest()->getMethod() === "POST" || $event->getRequest()->getMethod() === "PUT")
            ) {
                $message = $this->translator->trans('app.exception.bad_request_http_exception_body_no_empty');
            }
            if ($message) {
                $keys = explode("\n", trim($message));
                $translations = array_map(fn($key) => $this->translator->trans($key), $keys);
                $message = implode(', ', $translations);
                $message .= '.';
            }
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize([
                    'message' => $this->translator->trans($message)
                ], 'json'),
                $exception->getStatusCode(),
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

        if ($exception instanceof NotNullConstraintViolationException) {
            $fieldList = explode(",", explode(")", explode("(", $exception->getQuery()->getSQL())[1])[0]);
            foreach ($fieldList as $key => $field) {
                $fieldList[$key] = trim($field);
            }

            $messages = [];
            foreach ($exception->getQuery()->getParams() as $key => $value) {
                if (getType($value) === "NULL") {
                    $messages[$fieldList[$key - 1]] = $this->translator->trans(
                        'app.exception.not_null_constraint_violation_exception',
                        [
                        "%type%" => $exception->getQuery()->getTypes()[$key]
                        ]
                    );
                }
            }

            $event->setResponse(new JsonResponse(
                $this->serializer->serialize($messages, 'json'),
                Response::HTTP_BAD_REQUEST,
                [],
                true
            ));
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize([
                    'message' => $this->translator->trans('app.exception.method_not_allowed_http_exception', [
                        "%method%" => $event->getRequest()->getMethod(),
                        "%uri%" => $event->getRequest()->getUri(),
                        "%allows%" => $exception->getHeaders()['Allow']
                    ])
                ], 'json'),
                Response::HTTP_METHOD_NOT_ALLOWED,
                [],
                true
            ));
        }

        if ($exception instanceof UnexpectedValueException) {
            $message = $this->translator->trans('app.exception.unexpected_value_exception');
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize([
                    'message' => $message
                ], 'json'),
                Response::HTTP_BAD_REQUEST,
                [],
                true
            ));
        }
        if ($exception instanceof PartialDenormalizationException) {
            $messages = [];
            /** @var NotNormalizableValueException $e */
            foreach ($exception->getErrors() as $e) {
                $messages[$e->getPath()] = $this->translator->trans('app.exception.not_normalizable_value_exception', [
                    '%type%' => implode(', ', $e->getExpectedTypes()),
                    '%value%' => $e->getCurrentType()
                ]);
            }
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize($messages, 'json'),
                Response::HTTP_BAD_REQUEST,
                [],
                true
            ));
        }

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
                if ($exception->getMessage() === "The key \"email\" must be provided.") {
                    $message = $this->translator->trans('app.exception.bad_request_login_miss_username');
                }
            }
            if (
                $event->getRequest()->getContent() === "" &&
                ($event->getRequest()->getMethod() === "POST" || $event->getRequest()->getMethod() === "PUT")
            ) {
                $message = $this->translator->trans('app.exception.bad_request_http_exception_body_no_empty');
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

        if ($exception instanceof BadMethodCallException) {
            $message = $exception->getMessage();
            $event->setResponse(new JsonResponse(
                $this->serializer->serialize([
                'message' => $message
                ], 'json'),
                Response::HTTP_INTERNAL_SERVER_ERROR,
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
