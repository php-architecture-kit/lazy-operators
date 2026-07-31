<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;

class XorOperator implements BooleanValue
{
    public const KEY = 'xor';
    public const UID = 'aef9302c-c283-4c1c-ab7a-0b355e9ed445';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression&BooleanValue $left,
        public readonly Expression&BooleanValue $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() xor ($this->right)();
    }

    public static function formula(): string
    {
        return 'f(left, right) = left XOR right';
    }

    public static function description(): string
    {
        return 'XOR (exclusive OR) works by comparing two inputs and returning true (1) only when the inputs are different, meaning one is true and the other is false. It outputs false (0) if both inputs are the same (both true or both false).';
    }
}
