<?php
declare(strict_types = 1);

namespace src\Router;

#[\Attribute]
class Routes {
    public function __construct(public string $nameRoute, public string $path, public string $controller = '')
    {
    }
}