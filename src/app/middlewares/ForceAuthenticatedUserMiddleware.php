<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class ForceAuthenticatedUserMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if (!$this->container->auth->isLoggedIn()) {
            $this->container->flash->addMessage('mustBeLoggedInError', 'You must be logged in to do that.');
            return $response->withRedirect($this->container->router->pathFor('auth.login'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
