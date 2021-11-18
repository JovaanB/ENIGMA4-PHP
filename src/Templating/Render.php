<?php
declare(strict_types = 1);

namespace src\Templating;

use src\Templating\Functions\EscapeReplacement;
use src\Templating\Functions\Path;
use src\Templating\Functions\RawReplacement;

class Render{
    private ?RenderFunction $functions = null;

    public function __construct()
    {
        $this->functions = new RawReplacement();

        $pathFunction = new Path();

        $escapedReplacement = new EscapeReplacement();

        $escapedReplacement->setNext($pathFunction);

        $this->functions->setNext($escapedReplacement);
    }

    public function render(string $template, array $context = []){
        $path = __DIR__.'/../../templates/'.$template.'.phtml';
        if(!file_exists($path)){
            throw new \LogicException("templates '$path' not found");
        }

        $content = file_get_contents($path);

        $functions = $this->functions;
        while($functions instanceof RenderFunction){
            $functions = $functions->apply(content: $content, context: $context);
        }

        return $content;
    }
}