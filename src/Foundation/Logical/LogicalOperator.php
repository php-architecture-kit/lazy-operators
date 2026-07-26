<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;

interface LogicalOperator extends Expression
{
    public function __invoke(): bool;
}
