<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;

final class SpyExpression implements Expression
{
    public int $invocations = 0;

    public function __construct(
        private readonly mixed $value,
    ) {
    }

    public function __invoke(): mixed
    {
        $this->invocations++;

        return $this->value;
    }
}
