<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class HomeController extends BaseController
{
    public function index($request, $response)
    {
        return $this->container->view->render($response, 'index.twig');
    }
}
