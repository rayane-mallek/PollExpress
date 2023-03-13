<?php

namespace Framework\Listener;

use Framework\Events\RequestHandlingEvent;
use Framework\Service\PdfGenerator;
use Framework\Service\UserSessionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Twig\Environment;

class FrameworkListener implements EventSubscriberInterface {

    protected UserSessionManager $sessionManager;
    protected Environment $twig;
    protected PdfGenerator $pdfGenerator;

    public function __construct(UserSessionManager $sessionManager, Environment $twig, PdfGenerator $pdfGenerator) {
        $this->sessionManager = $sessionManager;
        $this->twig = $twig;
        $this->pdfGenerator = $pdfGenerator;
    }

    public function setupAppFromRequest(RequestHandlingEvent $event)
    {
        $this->sessionManager->updateSession();
        $this->twig->addGlobal('session', $this->sessionManager);
        $this->pdfGenerator->initialize();
    }

    public static function getSubscribedEvents()
    {
        return [
            'onRequestHandling' => 'setupAppFromRequest'
        ];
    }
}
