<?php
declare(strict_types=1);

namespace src\Controller;

use src\Database\Connector;
use src\Entity\User;
use src\Http\Response;
use src\Repository\UserRepository;
use src\Router\Router;
use src\Router\Routes;
use src\Security\Security;
use src\Templating\Render;

#[Security(mustConnected: false)]
#[Routes(path: '/login', nameRoute:'login')]
class Login implements Controller{
    public function __construct(private Router $router)
    {
    }

    public function verifyPassword(string $password = "", string $hashPassword = "") {
        $isVerify = false;
        if(password_verify($password, $hashPassword)){
            $isVerify = true;
        }

        return $isVerify;
    }

    public function display(): void
    {
        $userRepository = new UserRepository();

        if(!empty($_POST['pseudo']) && !empty($_POST['pass'])){
            $user = new User();
            $user->pseudo = $_POST['pseudo'];
            $user->pass = $_POST['pass'];
            $loggedUser = $userRepository->fetchOne($_POST['pseudo']);
            if($loggedUser) {
                $isVerify = $this->verifyPassword($_POST['pass'], $loggedUser->pass);
    
                if($isVerify) {
                    $_SESSION['isConnected'] = true;
                    $_SESSION['pseudo'] = $loggedUser->pseudo;
                }
            }
        }
        
        $content = (new Render())->render('layout', [
            'content' => (new Render())->render('login')
        ]);

        $response = new Response($content);
        $response->display();
    }
}