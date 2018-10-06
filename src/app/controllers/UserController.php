<?php

namespace App\Controllers;

use Slim\Container;
use Slim\Views\Twig;
use Illuminate\Database\Query\Builder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends BaseController
{
    protected $table;

    public function __construct(
        Container $container,
        Builder $table
    ) {
        parent::__construct($container);
        $this->table = $table;
    }

    public function getAllUsers(Request $request, Response $response, $args)
    {
        $users = $this->table->get();

        $this->container->view->render($response, 'user_getAllUsers.twig', [
            'users' => $users
        ]);

        return $response;
    }

    public function getUserById(Request $request, Response $response, $args)
    {
        $user = $this->table->find($args['id']);
        
        $this->container->view->render($response, 'user_getById.twig', [
            'user' => $user
        ]);
    }
}
