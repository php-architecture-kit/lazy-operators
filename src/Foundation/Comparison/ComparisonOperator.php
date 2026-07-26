<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;

interface ComparisonOperator extends Expression
{
    public function __invoke(): bool;
}
