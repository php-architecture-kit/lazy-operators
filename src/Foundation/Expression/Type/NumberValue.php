<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Type;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;

interface NumberValue extends Expression
{
    public function __invoke(): int|float;
}
