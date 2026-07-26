<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class Value implements Expression
{
    public function __construct(
        private readonly mixed $value,
    ) {
    }
    
    public function __invoke(): mixed
    {
        return $this->value;
    }
}
