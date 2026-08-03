<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Cast;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;

#[Group('Cast')]
#[Name('Boolean Cast')]
#[Formula('f(value) = (bool) value')]
#[Description('Boolean Cast coerces any expression\'s invoked result into a bool.')]
final class BooleanCast implements BooleanValue
{
    public const KEY = 'cast_boolean';
    public const UID = '375ff3b5-773b-4502-b374-4b3c4717bffb';
    public const VERSION = '1.0';

    public function __construct(
        public readonly Expression $value,
    ) {
    }

    public function __invoke(): bool
    {
        return (bool) ($this->value)();
    }
}
