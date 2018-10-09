<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;


class ValidationErrorsMiddleware extends BaseMiddleware
{
    public function __invoke(Request $request, Response $response, $next)
    {
        if (isset($_SESSION['errors'])) {
            $this->container->view->getEnvironment()->addGlobal('errors', $_SESSION['errors']);
            unset($_SESSION['errors']);
        }

        if (isset($_SESSION['formErrors'])) {
            $this->container->view->getEnvironment()->addGlobal('formErrors', $_SESSION['formErrors']);
            unset($_SESSION['formErrors']);
        }

        $response = $next($request, $response);
        return $response;
    }
}