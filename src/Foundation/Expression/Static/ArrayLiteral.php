<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Static;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\ArrayValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

/**
 * @template T
 *
 * @implements ArrayValue<T>
 */
#[Group('Static')]
#[Name('Array Literal')]
#[Formula('f(value) = value')]
#[Description('Array Literal wraps a raw PHP array as an Expression, returning it unchanged when invoked.')]
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
}
