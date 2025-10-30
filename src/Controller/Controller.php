<?php

namespace MyFramework\Controller;

use MyFramework\Router\Router;

abstract class Controller
{

    public abstract static function routes(Router $router);

}