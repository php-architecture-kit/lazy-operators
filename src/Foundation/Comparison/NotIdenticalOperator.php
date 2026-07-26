<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class NotIdenticalOperator implements ComparisonOperator
{
    public const KEY = 'not_identical';
    public const UID = '90d06e8e-fbdf-4ba7-afad-9be706714d81';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $left,
        public readonly Expression $right,
    ) {
    }
    
    public function __invoke(): bool
    {
        return ($this->left)() !== ($this->right)();
    }
}
