<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression;

use Closure;

class ExpressionTreeConfig
{
    /**
     * Builds the Decorator that wraps a single node. Called once per node a facade builds, with
     * that node as its only argument — so a decorator needing anything else (a logger, a cache
     * pool, a channel name) closes over it here instead of reaching for a static handle.
     *
     * Null means no decoration.
     *
     * @var (Closure(Expression): Decorator)|null
     */
    public readonly ?Closure $decoratorFactory;

    /**
     * @param (Closure(Expression): Decorator)|null $decoratorFactory
     */
    public static function decoratedBy(?Closure $decoratorFactory): self
    {
        return new self($decoratorFactory);
    }

    /**
     * @param (Closure(Expression): Decorator)|null $decoratorFactory
     */
    public function __construct(?Closure $decoratorFactory = null)
    {
        $this->decoratorFactory = $decoratorFactory;
    }
}
