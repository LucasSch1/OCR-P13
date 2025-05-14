<?php

namespace App\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JWTAuthenticationFailureSubscriber implements EventSubscriberInterface
{
    public function onAuthenticationFailureEvent(AuthenticationFailureEvent $event): void

    {
        $response = new JsonResponse([
            'status' => 401,
            'message' => 'Identifiants incorrects',
        ], Response::HTTP_UNAUTHORIZED);
        $event->setResponse($response);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            AuthenticationFailureEvent::class => 'onAuthenticationFailureEvent',
        ];
    }
}
