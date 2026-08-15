<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

trait DecoratesNodes
{
    /**
     * $config->decoratorFactory builds one Decorator per node, receiving the node being wrapped.
     * A user-supplied decorator only
     * implements the generic `Decorator extends Expression` contract, so on its own it can
     * silently drop whatever narrower interface the wrapped node already guaranteed (e.g.
     * SpaceshipOperator always implements IntegerValue). To avoid that, re-expose the decorated
     * result as whichever typed interface the *original, undecorated* node already implemented —
     * decorating a node should never make it less usable than it already was. A node that only
     * ever implemented the generic Expression contract in the first place (e.g.
     * IfElseOperator/SwitchCaseOperator, whose branches may differ in type) stays a bare
     * Expression either way: decoration never took anything away from it, so there's nothing to
     * restore, and it remains the caller's job to Cast if it needs a narrower type.
     */
    private static function decorate(Expression $node, ExpressionTreeConfig $config): Expression
    {
        if ($config->decoratorFactory === null) {
            return $node;
        }

        $decorated = ($config->decoratorFactory)($node);

        return match (true) {
            $node instanceof IntegerValue && !$decorated instanceof IntegerValue => new DecoratedIntegerValue($decorated),
            $node instanceof NumberValue && !$decorated instanceof NumberValue => new DecoratedNumberValue($decorated),
            $node instanceof BooleanValue && !$decorated instanceof BooleanValue => new DecoratedBooleanValue($decorated),
            $node instanceof StringValue && !$decorated instanceof StringValue => new DecoratedStringValue($decorated),
            default => $decorated,
        };
    }

    /**
     * Same as decorate(), narrowed to NumberValue for callers that already know (statically) that
     * $node is a NumberValue — decorate() guarantees the result is too.
     */
    private static function decorateNumber(Expression $node, ExpressionTreeConfig $config): NumberValue
    {
        $decorated = self::decorate($node, $config);

        assert($decorated instanceof NumberValue);

        return $decorated;
    }

    /**
     * Same as decorateNumber(), narrowed to IntegerValue.
     */
    private static function decorateInteger(Expression $node, ExpressionTreeConfig $config): IntegerValue
    {
        $decorated = self::decorate($node, $config);

        assert($decorated instanceof IntegerValue);

        return $decorated;
    }

    /**
     * Same as decorateNumber(), narrowed to BooleanValue.
     */
    private static function decorateBoolean(Expression $node, ExpressionTreeConfig $config): BooleanValue
    {
        $decorated = self::decorate($node, $config);

        assert($decorated instanceof BooleanValue);

        return $decorated;
    }

    /**
     * Same as decorateNumber(), narrowed to StringValue.
     */
    private static function decorateString(Expression $node, ExpressionTreeConfig $config): StringValue
    {
        $decorated = self::decorate($node, $config);

        assert($decorated instanceof StringValue);

        return $decorated;
    }
}
