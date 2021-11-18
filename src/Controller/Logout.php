<?php
declare(strict_types=1);

namespace src\Controller;

use src\Http\Response;
use src\Router\Router;
use src\Router\Routes;
use src\Security\Security;

#[Security(mustConnected: false)]
#[Routes(path: '/logout', nameRoute:'logout')]
class Logout implements Controller{
    public function __construct(private Router $router)
    {
    }


    public function display() : void {
        if($_SESSION['isConnected']){
            session_destroy();
        }

        $response = new Response('', 307, ['location: '. $this->router->getPath('login')]);
        $response->display();
    }
}