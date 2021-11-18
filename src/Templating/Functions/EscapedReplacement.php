<?php

declare(strict_types=1);

namespace src\Templating\Functions;

use src\Templating\RenderFunction;

class EscapedReplacement extends AbstractFunction {
    public function apply(&$content, $context): ?RenderFunction
    {
        foreach($context as $replacementKey => $replacementValue) {
            $content = str_replace("{{ $replacementKey }}", htmlspecialchars($replacementValue, ENT_QUOTES), $content);
        }
        return $this->next;
    }
}