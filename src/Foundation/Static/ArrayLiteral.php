<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Type\ArrayValue;

/**
 * @template T
 *
 * @implements ArrayValue<T>
 */
class ArrayLiteral implements ArrayValue
{
    public const KEY = 'array_literal';
    public const UID = '2ed3c810-ef39-4936-9e2c-d63eda96f29e';
    public const VERSION = '1.0';

    /**
     * @param array<array-key,T> $value
     */
    public function __construct(
        public readonly array $value,
    ) {
    }

    /**
     * @return array<array-key,T>
     */
    public function __invoke(): array
    {
        return $this->value;
    }

    public static function formula(): string
    {
        return 'f(value) = value';
    }
}
