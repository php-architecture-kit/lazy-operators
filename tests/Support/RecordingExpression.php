<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression;

final class RecordingExpression implements Expression
{
    /** @var array<int, mixed> */
    public static array $log = [];

    public function __construct(
        private readonly Expression $inner,
    ) {
    }

    public function __invoke(): mixed
    {
        $result = ($this->inner)();
        self::$log[] = $result;

        return $result;
    }

    public static function reset(): void
    {
        self::$log = [];
    }
}
