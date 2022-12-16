<?php

namespace Framework\Listener;

use Framework\Events\RequestHandlingEvent;
use Framework\Service\UserSessionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class FrameworkListener implements EventSubscriberInterface {

    protected UserSessionManager $sessionManager;
    protected Environment $twig;

    public function __construct(UserSessionManager $sessionManager, Environment $twig) {
        $this->sessionManager = $sessionManager;
        $this->twig = $twig;
    }

    public function setupAppFromRequest(RequestHandlingEvent $event) 
    {
        $this->sessionManager->updateSession();
        $this->twig->addGlobal('session', $this->sessionManager);
    }

    public static function getSubscribedEvents()
    {
        return [
            'onRequestHandling' => 'setupAppFromRequest'
        ];
    }
}
