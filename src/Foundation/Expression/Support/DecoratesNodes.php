<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

trait DecoratesNodes
{
    private static function decorate(Expression $node, PipelineConfig $config): Expression
    {
        if ($config->decorator === null) {
            return $node;
        }

        // $decorator is a prototype: its class is reinstantiated per node, so its
        // constructor must accept a single Expression (the node being wrapped).
        return new ($config->decorator::class)($node);
    }

    /**
     * Same as decorate(), but re-exposes the result as NumberValue: a user-supplied
     * decorator only implements the generic `Decorator extends Expression` contract, which would
     * otherwise break the narrowed constructor of the next arithmetic operator in the chain.
     */
    private static function decorateNumber(Expression $node, PipelineConfig $config): NumberValue
    {
        $decorated = self::decorate($node, $config);

        return $decorated instanceof NumberValue ? $decorated : new DecoratedNumberValue($decorated);
    }

    /**
     * Same as decorate(), but re-exposes the result as IntegerValue (see decorateNumber()).
     */
    private static function decorateInteger(Expression $node, PipelineConfig $config): IntegerValue
    {
        $decorated = self::decorate($node, $config);

        return $decorated instanceof IntegerValue ? $decorated : new DecoratedIntegerValue($decorated);
    }

    /**
     * Same as decorate(), but re-exposes the result as BooleanValue (see decorateNumber()).
     */
    private static function decorateBoolean(Expression $node, PipelineConfig $config): BooleanValue
    {
        $decorated = self::decorate($node, $config);

        return $decorated instanceof BooleanValue ? $decorated : new DecoratedBooleanValue($decorated);
    }

    /**
     * Same as decorate(), but re-exposes the result as StringValue (see decorateNumber()).
     */
    private static function decorateString(Expression $node, PipelineConfig $config): StringValue
    {
        $decorated = self::decorate($node, $config);

        return $decorated instanceof StringValue ? $decorated : new DecoratedStringValue($decorated);
    }
}
