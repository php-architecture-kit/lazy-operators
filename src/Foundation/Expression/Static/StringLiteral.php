<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Static;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

#[Group('Static')]
#[Name('String Literal')]
#[Formula('f(value) = value')]
#[Description('String Literal wraps a raw string as an Expression, returning it unchanged when invoked.')]
class StringLiteral implements StringValue
{
    public const KEY = 'string_literal';
    public const UID = 'd4fb334b-5bed-4fef-8b34-6b4d39af88c9';
    public const VERSION = '1.0';

    public function __construct(
        public readonly string $value,
    ) {
    }

    public function __invoke(): string
    {
        return $this->value;
    }
}
