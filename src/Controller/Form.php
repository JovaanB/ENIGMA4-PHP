<?php
declare(strict_types=1);

namespace src\Controller;

use src\Database\Connector;
use src\Entity\Mood;
use src\Http\Response;
use src\Repository\MoodRepository;
use src\Router\Router;
use src\Router\Routes;
use src\Security\Security;
use src\Templating\Render;

#[Security(mustConnected: false)]
#[Routes(path: '/form', nameRoute:'form')]
class Form implements Controller{
    public function __construct(private Router $router)
    {
    }

    public function display() : void{
        $pdo = Connector::getPDO();

        $moodRepository = new MoodRepository();

        if(!empty($_POST['mood'])){
            $mood = new Mood();
            $mood->mood = $_POST['mood'];
            $moodRepository->insert($mood);
        }

        $results = $moodRepository->fetchAll();

        $moods = '';
        foreach($results as $mood){
            $moods .= (new Render())->render('partials/mood', 
            [
                'mood' => $mood->mood, 
                'path' => $this->router->getPath('deleteMood', ['id' => $mood->id])
            ]);
        }

        $content = (new Render())->render('layout', [
            'content' => (new Render())->render('form', ['moods' => $moods])
        ]);

        $response = new Response($content);
        $response->display();
    }
}