<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Expression;

class Value implements Expression
{
    public const KEY = 'value';
    public const UID = '53dd5ea3-cebe-4034-b6d9-1005fc20ccc3';
    public const VERSION = '1.0';

    public function __construct(
        public readonly mixed $value,
    ) {
    }
    
    public function __invoke(): mixed
    {
        return $this->value;
    }

    public static function formula(): string
    {
        return 'f(value) = value';
    }
}
