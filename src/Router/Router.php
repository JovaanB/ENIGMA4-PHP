<?php
declare(strict_types = 1);

namespace src\Router;

use src\Router\Routes;
use src\Exception\NotFoundHttpException;
use src\Http\Response;
use src\Logger\LoggerAware;
use src\Security\Security;

class Router {
    use LoggerAware;
    
    private const CONTROLLERS_DIR = __DIR__.'/../Controller';

    /**
     * @var array<Route>
     */
    public array $routes = [];

    public function __construct()
    {
        $this->findController();
    }

    /**
     * @param array $args example ['foo' => 'bar']
     */
    public function getPath(string $routeName, array $args = []) {
        foreach($this->routes as $route){
            if($route->nameRoute === $routeName){
                $path = $route->path;
                $queryParametersString = http_build_query($args);
                if(!empty($queryParametersString)){
                    $path = $route->path.'?'.$queryParametersString;
                }
                return $path;
            }
        }    
    }

    public function findController(){
        if(!is_dir(self::CONTROLLERS_DIR)){
            throw new \LogicException(self::CONTROLLERS_DIR.' should be a valid directory');
        }

        foreach(scandir(self::CONTROLLERS_DIR) as $file){
            if($file === '.' || $file === '..'){
                continue;
            }

            $className = 'src\\Controller\\'.substr($file, 0, -4);
            $reflexion = new \ReflectionClass($className);

            $reflexionAttributesArray = $reflexion->getAttributes();

            foreach($reflexionAttributesArray as $att){
                $att = $att->newInstance();

                if($att instanceof Security) {
                    if($att->mustConnected && !isset($_SESSION['isConnected'])) {
                        header('location: /login');die;
                            $response = new Response('', 307, ['location: '. $this->getPath('login')]);
                            $response->display();
                    }
                }

                if($att instanceof Routes){
                    $att->controller = $className;
                    $this->routes[] = $att;
                } 
            }
        }

        if(empty($this->routes)){
            $this->log('No routes declared', 'error');
            trigger_error('No routes declared', E_USER_WARNING);
        }
    }

    public function getController(string $pathInfo) {
        foreach($this->routes as $route){
            if($route->path === $pathInfo){
                $this->log("Controller found '$route->controller' for path $pathInfo");
                return $route->controller;
            }
        }

        $this->log(message: "Controller not found for path $pathInfo", level: 'error', context: ['routes' => $this->routes]);
        throw new NotFoundHttpException();
    }
}