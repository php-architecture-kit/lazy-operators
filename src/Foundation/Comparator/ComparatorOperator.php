<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparator;

use PhpArchitecture\LazyOperators\Foundation\Expression;

interface ComparatorOperator extends Expression
{
    public function __invoke(): float|int;
}
