<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;

class LessThanOrEqualOperator implements BooleanValue
{
    public const KEY = 'less_than_or_equal';
    public const UID = '858cf616-69a8-4410-b5ed-816e1d2ae5a4';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() <= ($this->right)();
    }

    public static function formula(): string
    {
        return 'f(left, right) = left <= right';
    }
}
