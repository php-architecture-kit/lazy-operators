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
     * The prototype passed to the constructor, if the deprecated prototype form was used; null
     * otherwise, including whenever self::decoratedBy() was used. Nothing reads it —
     * DecoratesNodes::decorate() works from $decoratorFactory alone — so it is not a reliable
     * answer to "is this config decorated?"; $decoratorFactory is.
     *
     * @deprecated since 1.5.1, to be removed in 1.6.0. Use self::decoratedBy() and read
     *             $decoratorFactory instead. See #11.
     */
    public readonly ?Decorator $decorator;

    /**
     * @param (Closure(Expression): Decorator)|null $decoratorFactory
     */
    public static function decoratedBy(?Closure $decoratorFactory): self
    {
        return new self(decoratorFactory: $decoratorFactory);
    }

    /**
     * @param ?Decorator $decorator prototype form, deprecated since 1.5.1: only the class of this
     *                              instance is used, and a fresh one is built per node with the
     *                              node as its only constructor argument. Anything else the
     *                              instance was constructed with is therefore discarded — see #4.
     *                              Pass $decoratorFactory, or use self::decoratedBy(), to give a
     *                              decorator its dependencies.
     * @param (Closure(Expression): Decorator)|null $decoratorFactory builds one Decorator per node
     */
    public function __construct(
        ?Decorator $decorator = null,
        ?Closure $decoratorFactory = null,
    ) {
        if ($decorator !== null && $decoratorFactory === null) {
            $class = $decorator::class;
            $decoratorFactory = static fn (Expression $node): Decorator => new $class($node);
        }

        $this->decorator = $decorator;
        $this->decoratorFactory = $decoratorFactory;
    }
}
