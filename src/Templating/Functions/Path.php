<?php
declare(strict_types = 1);

namespace src\Templating\Functions;

use src\Router\Router;
use src\Templating\RenderFunction;

class Path extends AbstractFunction{
    public function apply(string &$content, array $context): ?RenderFunction
    {        
        $router = new Router();
        $this->generatePathWithArguments($router, $content, $context);
        $this->generatePath($router, $content, $context);

        return $this->next;
    }

    private function generatePath(Router $router, string &$content){
        preg_match_all('/{{ path\((.+)\) }}/m', $content, $matches);

        foreach($matches[0] as $key => $replacementKey){
            $chemin = $router->getPath($matches[1][$key]);
            $content = str_replace($replacementKey, $chemin, $content);
        }
    }

    private function generatePathWithArguments(Router $router, string &$content){
        preg_match_all('/{{ path\((.+)(?:, )({.+})\) }}/m', $content, $matches);

        foreach($matches[0] as $key => $replacementKey){
            $chemin = $router->getPath($matches[1][$key], json_decode($matches[2][$key], true));
            $content = str_replace($replacementKey, $chemin, $content);
        }
    }
}