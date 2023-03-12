<?php

namespace Framework;

use Config\ConfigurationGlobal;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Framework\AppFramework;
use Framework\Listener\FrameworkListener;
use Framework\Service\ServerSessionManager;
use Framework\Service\PdfGenerator;
use Framework\Service\Translator;
use Framework\Twig\Extension\TwigAppFrameworkExtension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\Controller\ContainerControllerResolver;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class AppInitializer {

    public function initializeApplication($configurationClass)
    {
        $routes = new RouteCollection();

        $container = new ContainerBuilder();
        $container->register('context', RequestContext::class);
        $container->register('matcher', UrlMatcher::class)
            ->setArguments([ $routes, new Reference('context') ]);
        $container->register('controller_resolver', ContainerControllerResolver::class)
            ->setArguments([ $container ]);

        $container->register('event_dispatcher', EventDispatcher::class)
            ->addMethodCall('addSubscriber', [new Reference('framework_listener')]);

        $container->register('session_manager', ServerSessionManager::class);

        $container->register('pdf_generator', PdfGenerator::class);

        $container->register('translator', Translator::class)
            ->setArguments([
                $configurationClass::defaultLanguage,
                $configurationClass::translations
            ]);

        $container->register('framework_listener', FrameworkListener::class)
            ->setArguments([ new Reference('session_manager') ])
            ->setArgument('twig', new Reference('twig'))
            ->setArgument('pdfGenerator', new Reference('pdf_generator'));

        foreach ($configurationClass::listeners as $listener) {
            $container->getDefinition('event_dispatcher')
                ->addMethodCall('addSubscriber', [ new Reference($listener) ]);
        }

        $container->register('argument_resolver', ArgumentResolver::class);
        $container->register('framework', AppFramework::class)
            ->setArguments([
                new Reference('matcher'),
                new Reference('controller_resolver'),
                new Reference('argument_resolver'),
                new Reference('event_dispatcher')
            ]);

        $container->register('url_generator', UrlGenerator::class)
            ->setArguments([ $routes, new Reference('context') ]);


        /* Twig */
        $views = $configurationClass::appRoot . '/' . $configurationClass::views;
        $twigLoader = new FilesystemLoader($views);
        $container->register('twig', Environment::class)
            ->setArguments([ $twigLoader, ["autoescape" => "html"] ])
            ->addMethodCall('addExtension', [new Reference('twig_app_framework_extension')]);

        /* Repository Manager */
        $repositoryManager = $configurationClass::repositoryManager;
        $container->register('repository_manager', $repositoryManager['manager'])
            ->setArguments([$repositoryManager['dataSourceParameters']])
            ->addMethodCall('registerRepositories', [$configurationClass::repositories]);

        /* Controllers */
        foreach($configurationClass::controllers as $key => $value) {
            $container->register($key, $value)
            ->setArguments([$container]);
        }

        /* Twig extensions */
        $container->register('twig_app_framework_extension', TwigAppFrameworkExtension::class)
            ->setArguments([
                new Reference('context'),
                new Reference('url_generator'),
                $configurationClass::debug
            ]);

        /* Routes */
        foreach($configurationClass::routes as $key => $value) {
            $route = new Route($value['path'], $value['parameters']);
            $route->setMethods($value['methods']);

            $routes->add($key, $route);
        }

        foreach($configurationClass::parameters as $name => $value) {
            $container->setParameter($name, $value);
        }

        /* Services */
        $configurationClass::services($container);

        return $container;
    }
}