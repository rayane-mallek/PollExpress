<?php

namespace Framework\Twig\Extension;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigAppFrameworkExtension extends AbstractExtension {

    protected RequestContext $context;
    protected UrlGeneratorInterface $generator;
    protected string $debug;

    public function __construct(RequestContext $context, UrlGeneratorInterface $generator, string $debug)
    {
        $this->context = $context;
        $this->generator = $generator;
        $this->debug = $debug;
    }

    public function asset(string $path)
    {   

        return $this->debug ? $this->context->getBaseUrl() . '/../assets/' . $path : $this->context->getBaseUrl() . '/assets/' . $path;
    }

    public function route(string $routeName, array $arguments = [])
    {
        return $this->generator->generate($routeName, $arguments);
    }

    public function getFunctions() 
    {
        return [
            new TwigFunction("asset", [$this, "asset"]),
            new TwigFunction("route", [$this, "route"]),
        ];
    }
}
