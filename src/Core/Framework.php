<?php

declare(strict_types=1);

namespace App\Core;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;

use function call_user_func_array;

class Framework
{
    protected UrlMatcher $matcher;
    protected ControllerResolver $controllerResolver;
    protected ArgumentResolver $argumentResolver;

    public function __construct(
        UrlMatcher $matcher,
        ControllerResolver $controllerResolver,
        ArgumentResolver $argumentResolver
    ) {
        $this->matcher            = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver   = $argumentResolver;
    }

    public function handle(Request $request): Response
    {
        try {
            $session = new Session();
            $session->start();
            $request->setSession($session);
            $this->matcher->getContext()->fromRequest($request);
            $request->attributes->add($this->matcher->match($request->getPathInfo()));
            $controller = $this->controllerResolver->getController($request);
            $arguments  = $this->argumentResolver->getArguments($request, $controller);
            return call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $exception) {

            return new Response('Not Found', 404);
        } catch (Exception $exception) {

            return new Response('An error occurred', 500);
        }
    }
}
