<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;

final class RecordingExpression implements Decorator
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

    public function unwrap(): Expression
    {
        return $this->inner;
    }

    public static function reset(): void
    {
        self::$log = [];
    }
}
