<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression;

/**
 * Marks an Expression as one that wraps another Expression, added around every node a facade
 * builds when an ExpressionTreeConfig carries a decorator factory.
 *
 * It adds nothing to Expression on purpose. A decorator's whole contract is "be invokable, and
 * do whatever you do around the node you were given" — the node arrives through the constructor,
 * chosen by the caller's own factory, so the interface has nothing left to require. Its job is to
 * let ExpressionTreeConfig and DecoratesNodes say what they mean in a type.
 */
interface Decorator extends Expression
{
}
