<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RequestListener
{
    private TokenStorageInterface $tokenStorage;
    private UrlGeneratorInterface $router;

    public function __construct(TokenStorageInterface $tokenStorage, UrlGeneratorInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if($this->tokenStorage->getToken() != null){
            $user = $this->tokenStorage->getToken()->getUser();
            if ($user instanceof User && $user->getBanned()) {
                $url = $this->router->generate('app_logout');
                $response = new RedirectResponse($url);
                $event->setResponse($response);
            }
        }
    }
}