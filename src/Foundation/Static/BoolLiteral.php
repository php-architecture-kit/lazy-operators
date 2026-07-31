<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Name('Boolean Literal')]
#[Formula('f(value) = value')]
#[Description('Boolean Literal wraps a raw true or false value as an Expression, returning it unchanged when invoked.')]
class BoolLiteral implements BooleanValue
{
    public const KEY = 'bool_literal';
    public const UID = '6a62058c-fc73-4df6-a9f8-07267abf58cc';
    public const VERSION = '1.0';

    public function __construct(
        public readonly bool $value,
    ) {
    }

    public function __invoke(): bool
    {
        return $this->value;
    }
}
