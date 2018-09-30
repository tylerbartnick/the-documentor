<?php

require_once(__DIR__ . '/../vendor/autoload.php');

// configure new Slim app for development
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$container = $app->getContainer();

// configure Slim\Twig-View as templating engine
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../res/views', [
        'cache' => false
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $c->router,
        $c->request->getUri()
    ));

    return $view;
};

// configure app controllers
$container['HomeController'] = function ($c) {
    return new \App\Controllers\HomeController($c);
};

// load routes
require_once(__DIR__ . '/../app/routes/routes.php');
