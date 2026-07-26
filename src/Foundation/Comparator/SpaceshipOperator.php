<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparator;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class SpaceshipOperator implements ComparatorOperator
{
    public function __construct(
        private readonly Expression $left,
        private readonly Expression $right,
    ) {}

    public function __invoke(): float|int
    {
        return ($this->left)() <=> ($this->right)();
    }
}
