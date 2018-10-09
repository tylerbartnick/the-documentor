<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class ForceGuestMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if ($this->container->auth->isLoggedIn()) {
            return $response->withRedirect($this->container->router->pathFor('index'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
