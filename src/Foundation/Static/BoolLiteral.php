<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;

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

    public static function formula(): string
    {
        return 'f(value) = value';
    }
}
