<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Cast;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\ArrayValue;

/**
 * @implements ArrayValue<mixed>
 */
#[Group('Cast')]
#[Name('Array Cast')]
#[Formula('f(value) = (array) value')]
#[Description('Array Cast coerces any expression\'s invoked result into an array, e.g. a list passed in through a port.')]
final class ArrayCast implements ArrayValue
{
    public const KEY = 'cast_array';
    public const UID = '616ad837-6361-4ce9-b577-78b0e574ee04';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $value,
    ) {
    }

    /**
     * @return array<array-key, mixed>
     */
    public function __invoke(): array
    {
        return (array) ($this->value)();
    }
}
