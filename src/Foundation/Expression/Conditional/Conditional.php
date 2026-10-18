<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\WrapsRawValues;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;

class Conditional
{
    use WrapsRawValues;
    use DecoratesNodes;

    public static function if(bool|BooleanValue $condition, ?ExpressionTreeConfig $config = null): IfBuilder
    {
        return IfBuilder::of($condition, $config);
    }

    public static function switch(mixed $subject, ?ExpressionTreeConfig $config = null): SwitchBuilder
    {
        return SwitchBuilder::of($subject, $config);
    }

    /**
     * A branch that ends the evaluation with an EvaluationFailedException, e.g. as the else of
     * Conditional::if() when the inputs have no valid result.
     */
    public static function fail(string|StringValue $message, ?ExpressionTreeConfig $config = null): Expression
    {
        $config ??= new ExpressionTreeConfig();

        return self::decorate(new FailOperator(self::decorateString(self::wrapAs(StringValue::class, $message), $config)), $config);
    }
}
