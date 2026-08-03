<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Cast;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

#[Group('Cast')]
#[Name('String Cast')]
#[Formula('f(value) = (string) value')]
#[Description('String Cast coerces any expression\'s invoked result into a string.')]
final class StringCast implements StringValue
{
    public const KEY = 'cast_string';
    public const UID = 'b3880bca-dea1-4425-bc7e-58538ae70b97';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $value,
    ) {
    }

    public function __invoke(): string
    {
        return (string) ($this->value)();
    }
}
