<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class CsrfViewMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        $this->container->view->getEnvironment()->addGlobal('csrf', [
            'fields' => "
                <input type=\"hidden\" name=\"" . $this->container->csrf->getTokenNameKey() . "\" value=\"" . $this->container->csrf->getTokenName() . "\">
                <input type=\"hidden\" name=\"" . $this->container->csrf->getTokenValueKey() . "\" value=\"" . $this->container->csrf->getTokenValue() . "\">
            "
        ]);

        $response = $next($request, $response);
        return $response;
    }
}
