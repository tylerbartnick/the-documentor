<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\BaseController;

use Slim\Container;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController extends BaseController
{
    public function __construct(
        Container $container
    ) {
        parent::__construct($container);
    }

    public function getSignUp(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, 'templates/auth/signup.twig');
    }

    public function postSignUp(Request $request, Response $response, $args)
    {
        // DANGEROUS --- No validation being done. Okay for now.
        $user = User::create([
            'username' => htmlspecialchars($request->getParam('signUpUsername')),
            'email' => htmlspecialchars($request->getParam('signUpEmail')),
            'password' => password_hash(htmlspecialchars($request->getParam('signUpPassword1')), PASSWORD_DEFAULT)
        ]);

        return $response->withRedirect($this->container->router->pathFor('index'));
    }
}
