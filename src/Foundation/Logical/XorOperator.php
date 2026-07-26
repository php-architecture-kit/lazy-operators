<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Logical;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class XorOperator implements LogicalOperator
{
    public const KEY = 'xor';
    public const UID = 'aef9302c-c283-4c1c-ab7a-0b355e9ed445';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
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
}
