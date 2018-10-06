<?php

$app->get('/', \App\Controllers\HomeController::class . ':index');
$app->get('/users', \App\Controllers\UserController::class . ':getAllUsers');
$app->get('/users/{id}', \App\Controllers\UserController::class . ':getUserById');
