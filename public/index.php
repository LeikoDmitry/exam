<?php

declare(strict_types=1);

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use App\Core\Framework;

function application(): void
{
    require_once __DIR__ . '/../vendor/autoload.php';
    $routes = require_once __DIR__ . '/../config/routes.config.php';
    $request = Request::createFromGlobals();
    $context = new RequestContext();
    $matcher = new UrlMatcher($routes, $context);
    $controllerResolver = new ControllerResolver();
    $argumentResolver = new ArgumentResolver();
    $framework = new Framework($matcher, $controllerResolver, $argumentResolver);
    $response = $framework->handle($request);
    $response->send();
}

application();