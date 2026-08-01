<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Cast;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Type\FloatValue;

#[Group('Cast')]
#[Name('Float Cast')]
#[Formula('f(value) = (float) value')]
#[Description('Float Cast coerces any expression\'s invoked result into a float.')]
final class FloatCast implements FloatValue
{
    public const KEY = 'cast_float';
    public const UID = '48665155-cc22-4d4b-a1d1-511c077601ca';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $value,
    ) {
    }

    public function __invoke(): float
    {
        return (float) ($this->value)();
    }
}
