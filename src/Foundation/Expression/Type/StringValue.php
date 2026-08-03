<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Type;

use PhpArchitecture\LazyOperators\Foundation\Expression;

interface StringValue extends Expression
{
    public function __invoke(): string;
}
