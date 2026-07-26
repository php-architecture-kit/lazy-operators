<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;

interface ArithmeticOperator extends Expression
{
    public function __invoke(): float|int;
}
