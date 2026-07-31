<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;

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

    public static function formula(): string
    {
        return 'f(value) = value';
    }
}
