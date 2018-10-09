<?php

namespace App\Controllers\Auth;

use App\Models\User;
use App\Controllers\BaseController;

use Slim\Container;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Support\Facades\DB;

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
        $errors = [];
        $formErrors = [];
        $params = $request->getParsedBody();

        // validate username
        if (isset($params['username']) && (strlen($params['username']) > 3 && strlen($params['username']) < 17)) {
            $user = User::where('username', '=', $params['username'])->first();
            if ($user) {
                $errors[] = "Username already exists. Please choose another.";
                $formErrors['username'] = true;
            }
        } else {
            $errors[] = "Username must be between 4 and 16 characters long.";
            $formErrors['username'] = true;
        }

        // validate email
        if (isset($params['email'])) {
            if (filter_var($params['email'], FILTER_VALIDATE_EMAIL)) {
                $user = User::where('email', '=', $params['email'])->first();
                if ($user) {
                    $errors[] = "That email is already registered. Please login using that email.";
                    $formErrors['email'] = true;
                }
            } else {
                $errors[] = 'Supplied email is not valid. Please check and edit.';
                $formErrors['email'] = true;
            }
        } else {
            $errors[] = 'Please enter a valid email address.';
            $formErrors['email'] = true;
        }

        // validate password
        if ((isset($params['password1']) && isset($params['password2'])) &&
            ($params['password1'] === $params['password2'])
        ) {
            $password = $params['password1'];
            if (strlen($password) < 8) {
                $errors[] = 'Password must be at least 8 characters long.';
                $formErrors['passwords'] = true;
            }
        
            if (!preg_match("/[0-9]+/", $password)) {
                $errors[] = 'Password must include at least one number.';
                $formErrors['passwords'] = true;
            }
        
            if (!preg_match("/[a-z]+/", $password)) {
                $errors[] = 'Password must include at least one lowercase letter.';
                $formErrors['passwords'] = true;
            }

            if (!preg_match("/[A-Z]+/", $password)) {
                $errors[] = 'Password must include at least one uppercase letter.';
                $formErrors['passwords'] = true;
            }
            
            if (!preg_match("/[!@#$%&*?+-]+/", $password)) {
                $errors[] = 'Password must include at least one special character (! @ # $ % & * ? + -).';
                $formErrors['passwords'] = true;
            }
        } else {
            $errors[] = 'Passwords do not match.';
            $formErrors['passwords'] = true;
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['formErrors'] = $formErrors;
            $_SESSION['prevData'] = $request->getParams();
            return $response->withRedirect($this->container->router->pathFor('auth.signup'));
        }

        $user = User::create([
            'username' => $request->getParam('username'),
            'email'    => $request->getParam('email'),
            'password' => password_hash($request->getParam('password1'), PASSWORD_BCRYPT, [
                'cost' => 15
            ])
        ]);

        $authenticated = $this->container->auth->authenticate(
            $request->getParam('email'), 
            $request->getParam('password1')
        );

        return $response->withRedirect($this->container->router->pathFor('index'));
    }

    public function getLogin(Request $request, Response $response, $args)
    {
        return $this->container->view->render($response, 'templates/auth/login.twig');
    }

    public function postLogin(Request $request, Response $response, $args)
    {
        $authenticated = $this->container->auth->authenticate(
            $request->getParam('email'), 
            $request->getParam('password')
        );

        if (!$authenticated) {
            return $response->withRedirect($this->container->router->pathFor('auth.login'));
        }

        return $response->withRedirect($this->container->router->pathFor('index'));
    }

    public function getLogout(Request $request, Response $response, $args)
    {
        $this->container->auth->logout();

        return $response->withRedirect($this->container->router->pathFor('index'));
    }
}
