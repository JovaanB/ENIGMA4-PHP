<?php
declare(strict_types=1);

namespace src\Controller;

use src\Entity\User;
use src\Http\Response;
use src\Repository\UserRepository;
use src\Router\Router;
use src\Router\Routes;
use src\Security\Security;
use src\Templating\Render;

#[Security(mustConnected: false)]
#[Routes(path: '/register', nameRoute:'register')]
class Register implements Controller{
    public function __construct(private Router $router)
    {
    }

    public function display(): void
    {
        $userRepository = new UserRepository();

        if(!empty($_POST['pseudo']) && !empty($_POST['pass'])) {
            $user = new User();
            $user->pseudo = $_POST['pseudo'];
            $user->pass = $_POST['pass'];
            $userRepository->insert($user);
            $response = new Response('', 307, ['location: '. $this->router->getPath('login')]);
            $response->display();
        } else {
            $content = (new Render())->render('layout', [
                'content' => (new Render())->render('register')
            ]);
        }
        $response = new Response($content);
        $response->display();
    }
}