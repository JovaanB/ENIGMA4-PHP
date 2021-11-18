<?php

declare(strict_types=1);

namespace src\Security;

#[\Attribute]
class Security {
    
    public function __construct(public bool $mustConnected)
    {
    }
}