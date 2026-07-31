<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;

class AndOperator implements BooleanValue
{
    public const KEY = 'and';
    public const UID = '72c13fa3-d055-4de5-bc79-1207b9c8757b';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression&BooleanValue $left,
        public readonly Expression&BooleanValue $right,
    ) {}

    public function __invoke(): bool
    {
        return ($this->left)() && ($this->right)();
    }

    public static function formula(): string
    {
        return 'f(left, right) = left AND right';
    }

    public static function description(): string
    {
        return 'AND (logical AND) works by comparing two inputs and returning true (1) only when both inputs are true. It outputs false (0) if any of the inputs are false, meaning one or both are false.';
    }
}
