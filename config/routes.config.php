<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing;
use App\Controller\AccountController;
use App\Core\TemplateRender;

$routes = new RouteCollection();
$account = new AccountController(new TemplateRender());
$routes->add('home', new Routing\Route('/', [
    '_controller' => [$account, 'index'],
]));
$routes->add('login', new Routing\Route('/login', [
    '_controller' => [$account, 'login'],
]));
$routes->add('logout', new Routing\Route('/logout', [
    '_controller' => [$account, 'logout'],
]));
$routes->add('payout', new Routing\Route('/payout', [
    '_controller' => [$account, 'payout'],
]));

return $routes;