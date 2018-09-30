<?php

namespace App\Controllers;

use Slim\Container as Container;

class BaseController
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
