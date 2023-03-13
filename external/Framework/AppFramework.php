<?php

namespace Framework;

use Exception;
use Framework\Events\RequestHandlingEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class AppFramework
{
   private $urlMatcher;
   private $controllerResolver;
   private $argumentResolver;
   private $eventDispatcher;

   public function __construct(
      UrlMatcherInterface $urlMatcher,
      ControllerResolverInterface $controllerResolver,
      ArgumentResolverInterface $argumentResolver,
      EventDispatcherInterface $eventDispatcher
   )
   {
      $this->urlMatcher = $urlMatcher;
      $this->controllerResolver = $controllerResolver;
      $this->argumentResolver = $argumentResolver;
      $this->eventDispatcher = $eventDispatcher;
   
   }

   public function handle(Request $request): Response
   {
      $event = new RequestHandlingEvent($request);
      $this->eventDispatcher->dispatch($event, 'onRequestHandling');

      //Met à jour les informations relatives au contexte de la requête avec la nouvelle requête traitée
      $this->urlMatcher->getContext()->fromRequest($request);
      try{
            $request->attributes->add($this->urlMatcher->match($request->getPathInfo()));

            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
      } catch (ResourceNotFoundException $exception) {
            $response = new Response("Page introuvable!", 404);
      } catch (Exception $exception) {
            $response = new Response("Erdreur : {$exception }", 500);
      }

      return $response;
   }
}

