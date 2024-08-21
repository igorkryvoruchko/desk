<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotFoundExceptionListener
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception->getStatusCode() == 404) {
            $message = [
                'data' => [],
                'errors' => [
                    $this->translator->trans('not-found-exception')
                ]
            ];
        } else {
            $message = [
                'data' => [],
                'errors' => [
                    $exception->getMessage()
                ]
            ];
        }

        $response = new JsonResponse();
        $response->setData($message);

        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace(array_merge($exception->getHeaders(), ["Content-Type" => "application/json"]));
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}