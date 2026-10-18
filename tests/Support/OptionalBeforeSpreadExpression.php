<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;

/**
 * No library node has an optional argument before a spread one; this fixture covers that shape.
 */
final class OptionalBeforeSpreadExpression implements NumberValue
{
    public const KEY = 'test_optional_before_spread';
    public const UID = '6a9f0b4e-1d2c-4b8a-9e3f-5c7d8e9f0a1b';
    public const VERSION = '1.0';

    /**
     * @var NumberValue[]
     */
    private array $rest;

    public function __construct(
        private readonly ?NumberValue $first = null,
        NumberValue ...$rest,
    ) {
        $this->rest = $rest;
    }

    public function __invoke(): int|float
    {
        return array_sum(array_map(static fn (NumberValue $value): int|float => $value(), $this->rest))
            + ($this->first !== null ? ($this->first)() : 0);
    }
}
