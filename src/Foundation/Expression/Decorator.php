<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression;

interface Decorator extends Expression
{
    public function unwrap(): Expression;
}
