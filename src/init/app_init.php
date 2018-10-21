<?php

session_start();

require_once(__DIR__ . '/../../vendor/autoload.php');

// configure new Slim app for development
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true,
        'db' => [
            'driver'        => 'mysql',
            'host'          => 'localhost',
            'database'      => 'documentor',
            'username'      => 'doc_webadmin',
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
$container->get('db');

$container['auth'] = function ($c) {
    return new \App\Helpers\Auth\AuthenticationHelper;
};

$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages();
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

    $view->getEnvironment()->addGlobal('auth', [
        'isLoggedIn' => $c->auth->isLoggedIn(),
        'currentUser' => $c->auth->getCurrentUser()
    ]);

    $view->getEnvironment()->addGlobal('flash', $c->flash);

    return $view;
};

// configure app controllers
$container[App\Controllers\IndexController::class] = function ($c) {
    return new \App\Controllers\IndexController($c);
};

$container[App\Controllers\Auth\AuthController::class] = function ($c) {
    return new \App\Controllers\Auth\AuthController($c);
};

$container[App\Controllers\Users\UserController::class] = function ($c) {
    $table = $c->get('db')->table('users');
    return new \App\Controllers\Users\UserController($c, $table);
};

$container[App\Controllers\Guides\GuideController::class] = function ($c) {
    return new \App\Controllers\Guides\GuideController($c);
};

// configure middleware
$container['csrf'] = function ($c) {
    return new \Slim\Csrf\Guard;
};

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\FormDataMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

// load routes
require_once(__DIR__ . '/../app/routes/routes.php');
