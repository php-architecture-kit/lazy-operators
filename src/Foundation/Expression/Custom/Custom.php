<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Custom;

use Closure;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class Custom
{
    use WrapsRawValues;

    public static function callback(Closure $callback, mixed ...$arguments): CallbackOperator
    {
        return new CallbackOperator(
            $callback,
            ...array_map(static fn (mixed $argument) => self::wrap($argument), $arguments),
        );
    }
}
