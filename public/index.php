<?php
declare(strict_types = 1);

spl_autoload_register(function(string $className){
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $className).'.php';
    require_once(__DIR__."/../$path");
});

use src\Controller\Controller;
use src\Exception\NotFoundHttpException;
use src\Http\Response;
use src\Router\Router;
use src\Templating\Render;

session_start();
$router = new Router();

try{
    $controllerName = $router->getController($_SERVER['PATH_INFO'] ?? '/home');
    $controller = new $controllerName($router);

    if(!$controller instanceof Controller){
        throw new LogicException(sprintf('Controller "%s" must implement interface', $controllerName, Controller::class));
    }

    $controller -> display();
}
catch(NotFoundHttpException $exception){
    //Gérer la 404
    echo'<pre>'; var_dump($exception); echo '</pre>';
    $content = $content = (new Render())->render('layout', [
        'content' => (new Render())->render('404'),
    ]);
    $response = new Response($content, 404);
    $response->display();
}
catch(Exception $exception){
    //Gérer les 500
    echo'<pre>'; var_dump($exception); echo '</pre>';
    $response = new Response($exception->getMessage(), 500);
    $response->display();
}