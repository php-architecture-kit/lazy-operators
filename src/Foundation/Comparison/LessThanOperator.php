<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;

class LessThanOperator implements BooleanValue
{
    public const KEY = 'less_than';
    public const UID = '4898fe1a-0e23-46f9-b45a-29644b84cb36';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() < ($this->right)();
    }

    public static function formula(): string
    {
        return 'f(left, right) = left < right';
    }
}
