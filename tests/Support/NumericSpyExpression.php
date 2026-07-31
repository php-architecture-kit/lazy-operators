<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;

final class NumericSpyExpression implements NumberValue
{
    public int $invocations = 0;

    public function __construct(
        private readonly int|float $value,
    ) {
    }

    public function __invoke(): int|float
    {
        $this->invocations++;

        return $this->value;
    }
}
