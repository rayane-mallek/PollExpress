<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Config\Environment\Development\ConfigurationDevelopment;
use Symfony\Component\HttpFoundation\Request;
use TheFeed\TheFeed;

$container = (new TheFeed())->initializeApplication(ConfigurationDevelopment::class);
$appFramework = $container->get('framework');

$response = $appFramework->handle(Request::createFromGlobals());
$response->send();
