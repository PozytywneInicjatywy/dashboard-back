<?php

declare(strict_types=1);

namespace PozytywneInicjatywy\Dashboard\Symfony\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CorsListener
{
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $route = $event->getRequest()->getRequestUri();

        if (preg_match("/^\/api/", $route)) {
            $response = $event->getResponse();
            $response->headers->set("Access-Control-Allow-Origin", "*");
        }
    }
}
