<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class FormDataMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if (isset($_SESSION['prevData'])) {
            $this->container->view->getEnvironment()->addGlobal('prevData', $_SESSION['prevData']);
            unset($_SESSION['prevData']);
        }

        $response = $next($request, $response);
        return $response;
    }
}
