<?php

namespace Framework\Application;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller {

    protected ContainerInterface $containerInterface;

    public function __construct(ContainerInterface $containerInterface)
    {
        $this->containerInterface = $containerInterface;
    }

    public function render($twigPath, $arguments)
    {
        $content = $this->containerInterface->get('twig')->render($twigPath, $arguments);

        return new Response($content, Response::HTTP_OK);
    }

    public function redirectToRoute(string $routeName, array $arguments = [])
    {
        $urlGenerator = $this->containerInterface->get('url_generator');

        return $this->redirectTo($urlGenerator->generate($routeName, $arguments));
    }

    public function redirectTo(string $url)
    {
        return new RedirectResponse($url);
    }

    public function addFlash($category, $message)
    {
        $this->containerInterface->get('session_manager')->addFlash($category, $message);
    }

}