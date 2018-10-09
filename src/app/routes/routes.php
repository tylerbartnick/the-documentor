<?php

// index
$app->get('/', \App\Controllers\IndexController::class . ':index')->setName('index');

// auth
$app->get('/auth/signup[/]', \App\Controllers\Auth\AuthController::class . ':getSignUp')->setName('auth.signup');
$app->post('/auth/signup[/]', \App\Controllers\Auth\AuthController::class . ':postSignUp');
$app->get('/auth/login[/]', \App\Controllers\Auth\AuthController::class . ':getLogin')->setName('auth.login');
$app->post('/auth/login[/]', \App\Controllers\Auth\AuthController::class . ':postLogin');

// users
$app->get('/users[/]', \App\Controllers\Users\UserController::class . ':getAllUsers')->setName('users.getAll');
$app->get('/users/{id:[0-9]+}', \App\Controllers\Users\UserController::class . ':getUserById')->setName('users.getById');
