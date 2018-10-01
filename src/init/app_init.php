<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

// configure new Slim app for development
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver'        => 'mysql',
            'host'          => 'localhost',
            'database'      => 'test_db',
            'username'      => 'db_tester',
            'password'      => 'TesT1234!',
            'charset'       => 'utf8',
            'collation'     => 'utf8_unicode_ci',
            'prefix'        => ''
        ]
    ]
]);

$container = $app->getContainer();

$container['db'] = function ($c) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($c['settings']['db']);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

// configure Slim\Twig-View as templating engine
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../app/views/', [
        'cache' => false,
        'debug' => true,
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $c->router,
        $c->request->getUri()
    ));

    $view->addExtension(new \Twig_Extension_Debug());

    return $view;
};

// configure app controllers
$container[App\Controllers\HomeController::class] = function ($c) {
    return new \App\Controllers\HomeController($c);
};

$container[App\Controllers\UserController::class] = function ($c) {
    $table = $c->get('db')->table('users');
    return new \App\Controllers\UserController($c, $table);
};


// load routes
require_once(__DIR__ . '/../app/routes/routes.php');
