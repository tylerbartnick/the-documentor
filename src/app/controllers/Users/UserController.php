<?php

namespace App\Controllers\Users;

use \App\Models\User;
use \App\Controllers\BaseController;

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
        $users = User::all();

        $this->container->view->render($response, 'templates/users/getAllUsers.twig', [
            'users' => $users
        ]);

        return $response;
    }

    public function getUserByUsername(Request $request, Response $response, $args)
    {
        $user = User::where('username', '=', $args['username'])->first();
        
        $this->container->view->render($response, 'templates/users/getByUsername.twig', [
            'user' => $user,
            'givenUsername' => $args['username']
        ]);
    }
}
