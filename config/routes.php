<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'SamlAuthenticationPlugin',
    ['path' => '/saml-auth'],
    function (RouteBuilder $routes) {
        $routes->connect('/login', ['controller' => 'Sso', 'action' => 'login']);
        $routes->connect('/logout', ['controller' => 'Sso', 'action' => 'logout']);
        $routes->connect('/metadata', ['controller' => 'Sso', 'action' => 'metadata']);

        $routes->fallbacks(DashedRoute::class);
    }
);
