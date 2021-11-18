<?php
declare(strict_types=1);

namespace src\Controller;

use src\Http\Response;
use src\Router\Router;
use src\Router\Routes;
use src\Security\Security;
use src\Templating\Render;

#[Security(mustConnected: true)]
#[Routes(path: '/info', nameRoute:'info')]
class Info implements Controller{ 
    public function __construct(private Router $router)
    {
    }

    public function display() : void {
        $content = (new Render())->render('layout', [
            'content' => (new Render())->render('info')
        ]);

        $response = new Response($content);
        $response->display();
    }
}