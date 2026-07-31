<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;

final class StringSpyExpression implements StringValue
{
    public int $invocations = 0;

    public function __construct(
        private readonly string $value,
    ) {
    }

    public function __invoke(): string
    {
        $this->invocations++;

        return $this->value;
    }
}
