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
     * @var (Closure(Expression): Decorator)|null
     */
    public readonly ?Closure $decoratorFactory;

    /**
     * @param (Closure(Expression): Decorator)|null $decoratorFactory
     */
    public static function decoratedBy(?Closure $decoratorFactory): self
    {
        return new self(decoratorFactory: $decoratorFactory);
    }

    /**
     * @param ?Decorator                            $decorator        prototype form: only its class is used, a
     *                                                                fresh instance is built per node, so whatever
     *                                                                it was constructed with is discarded. Kept
     *                                                                working for existing callers; prefer
     *                                                                self::decoratedBy().
     * @param (Closure(Expression): Decorator)|null $decoratorFactory
     */
    public function __construct(
        public readonly ?Decorator $decorator = null,
        ?Closure $decoratorFactory = null,
    ) {
        if ($decorator !== null && $decoratorFactory === null) {
            $class = $decorator::class;
            $decoratorFactory = static fn (Expression $node): Decorator => new $class($node);
        }

        $this->decoratorFactory = $decoratorFactory;
    }
}
