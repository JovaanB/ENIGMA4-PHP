<?php
declare(strict_types=1);

namespace src\Controller;

use src\Http\Response;
use src\Repository\MoodRepository;
use src\Router\Router;
use src\Router\Routes;
use src\Security\Security;

#[Security(mustConnected: false)]
#[Routes(path: '/deleteMood', nameRoute:'deleteMood')]
class DeleteMood implements Controller{
    public function __construct(private Router $router)
    {
    }


    public function display() : void {
        $moodId = $_GET['id'] ?? null;
        if($moodId !== null){
            $moodRepository = new MoodRepository();
            $moodRepository->delete($moodId);
        }

        $response = new Response('', 307, ['location: '. $this->router->getPath('form')]);
        $response->display();
    }
}