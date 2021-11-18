<?php
declare(strict_types=1);

namespace src\Controller;

use src\Http\Response;
use src\Router\Router;
use src\Router\Routes;
use src\Security\Security;
use src\Templating\Render;

#[Security(mustConnected: true)]
#[Routes(path: '/query', nameRoute:'query')]
class Query implements Controller{
    public function __construct(private Router $router)
    {
    }

    public function display() : void{      
        $name = $_GET['name'] ?? 'anonyme';
        $content = (new Render())->render('layout', [
            'content' => (new Render())->render('query', ['name' => $name]),
        ]);

        $response = new Response($content);
        $response->display();
    }
}