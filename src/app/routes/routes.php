<?php

// index
$app->get('/', \App\Controllers\IndexController::class . ':index')->setName('index');

$app->get('/guides[/]', \App\Controllers\Guides\GuideController::class . ':getAllGuides')->setName('guides.getAllGuides');

// protected - requesting user must NOT be authenticated to access
// redirect to index page otherwise
$app->group('', function () use ($app) {
    $app->get('/auth/signup[/]', \App\Controllers\Auth\AuthController::class . ':getSignUp')->setName('auth.signup');
    $app->post('/auth/signup[/]', \App\Controllers\Auth\AuthController::class . ':postSignUp');
    $app->get('/auth/login[/]', \App\Controllers\Auth\AuthController::class . ':getLogin')->setName('auth.login');
    $app->post('/auth/login[/]', \App\Controllers\Auth\AuthController::class . ':postLogin');
})->add(new \App\Middleware\ForceGuestMiddleware($container));

// protected - requesting user must be authenticated to access
// redirect to login page otherwise
$app->group('', function () use ($app) {
    $app->get('/auth/logout[/]', \App\Controllers\Auth\AuthController::class . ':getLogout')->setName('auth.logout');
    $app->get('/users[/]', \App\Controllers\Users\UserController::class . ':getAllUsers')->setName('users.getAll');
    $app->get('/users/{username:[a-zA-Z0-9]+}', \App\Controllers\Users\UserController::class . ':getUserByUsername')->setName('users.getUserByUsername');

    $app->get('/guides/create[/]', \App\Controllers\Guides\GuideController::class . ':getCreateGuide')->setName('guides.getCreateGuide');
    $app->post('/guides/create[/]', \App\Controllers\Guides\GuideController::class . ':postCreateGuide')->setName('guides.postCreateGuide');
})->add(new \App\Middleware\ForceAuthenticatedUserMiddleware($container));
