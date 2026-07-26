<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Custom;

use Closure;
use PhpArchitecture\LazyOperators\Foundation\Expression;

class CallbackOperator implements Expression
{
    public const KEY = 'callback';
    public const UID = '53ceb583-f1d3-40c3-a8ae-7d9ffdffede2';
    public const VERSION = '1.0';

    /**
     * @var Expression[]
     */
    public readonly array $arguments;

    public function __construct(
        public readonly Closure $callback,
        Expression ...$arguments,
    ) {
        $this->arguments = $arguments;
    }

    public function __invoke(): mixed
    {
        return ($this->callback)(...array_map(static fn(Expression $expr) => $expr(), $this->arguments));
    }
}
