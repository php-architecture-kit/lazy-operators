<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Cast;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;

#[Group('Cast')]
#[Name('Integer Cast')]
#[Formula('f(value) = (int) value')]
#[Description('Integer Cast coerces any expression\'s invoked result into an int.')]
final class IntegerCast implements IntegerValue
{
    public const KEY = 'cast_integer';
    public const UID = '56ecc5e9-60d4-4fe9-9e0e-385c30fce9e5';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $value,
    ) {
    }

    public function __invoke(): int
    {
        return (int) ($this->value)();
    }
}
