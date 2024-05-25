<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthenticationFailureListener
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event): void
    {
        $data = [
            'data' => [],
            'errors' => [
                'email' => [$this->translator->trans('sign-in-error-email')],
                'password' => [$this->translator->trans('sign-in-error-password')]
            ]
        ];

        $response = new JsonResponse($data, Response::HTTP_UNAUTHORIZED, ['WWW-Authenticate' => 'Bearer']);

        $event->setResponse($response);
    }
}