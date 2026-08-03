<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Type;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;

interface StringValue extends Expression
{
    public function __invoke(): string;
}
