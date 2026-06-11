<?php

use Helix\Http\Response;
use Helix\View\Template;

$router->add('GET', '/', function () {
    $template = new Template(__DIR__ . '/../app/Http/Views');

    $app = \Helix\Foundation\Application::getInstance();
    $router = $app->getContainer()->get(\Helix\Routing\Router::class);
    $routes = $router->compileRoutes();

    $html = $template->render('welcome', [
        'routes' => $routes,
    ]);

    return Response::html($html);
});
