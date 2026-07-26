<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparator;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class SpaceshipOperator implements ComparatorOperator
{
    public const KEY = 'spaceship';
    public const UID = '611d67d6-5612-449a-89bc-8ece8bc39a86';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {}

    public function __invoke(): float|int
    {
        return ($this->left)() <=> ($this->right)();
    }

    public static function formula(): string
    {
        return 'f(left, right) = sgn(left - right)';
    }
}
