<?php
declare(strict_types=1);

namespace src\Controller;

use src\Entity\Chat;
use src\Http\Response;
use src\Repository\ChatRepository;
use src\Router\Router;
use src\Router\Routes;
use src\Security\Security;
use src\Templating\Render;

#[Security(mustConnected: false)]
#[Routes(path: '/pasdaccord', nameRoute:'pasdaccord')]
class PasDaccord implements Controller{
    public function __construct(private Router $router)
    {
    }

    public function display(): void
    {
        $chatRepository = new ChatRepository();

        if(!empty($_POST['message'])){
            $chat = new Chat();
            $chat->pseudo = $_SESSION['pseudo'] ?? 'anonyme';
            $chat->message = $_POST['message'];
            $chatRepository->insert($chat);
        }

        $results = $chatRepository->fetchAll();

        $chats = '';
        foreach($results as $chat){
            $chats .= (new Render())->render('partials/chat', 
            [
                'chat' => $chat->message, 
                'pseudo' => $chat->pseudo,
            ]);
        }

        $content = (new Render())->render('layout', [
            'content' => (new Render())->render('pasdaccord', ['chats' => $chats])
        ]);

        $response = new Response($content);
        $response->display();
    }
}