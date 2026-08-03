<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class CaseOfSwitchCase
{
    public function __construct(
        public readonly Expression $condition,
        public readonly Expression $value,
    ) {}
}
