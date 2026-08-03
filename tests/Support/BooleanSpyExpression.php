<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;

final class BooleanSpyExpression implements BooleanValue
{
    public int $invocations = 0;

    public function __construct(
        private readonly bool $value,
    ) {
    }

    public function __invoke(): bool
    {
        $this->invocations++;

        return $this->value;
    }
}
