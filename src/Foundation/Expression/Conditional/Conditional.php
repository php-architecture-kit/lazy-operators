<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\BooleanValue;

class Conditional
{
    public static function if(bool|BooleanValue $condition, ?ExpressionTreeConfig $config = null): IfBuilder
    {
        return IfBuilder::of($condition, $config);
    }

    public static function switch(mixed $subject, ?ExpressionTreeConfig $config = null): SwitchBuilder
    {
        return SwitchBuilder::of($subject, $config);
    }
}
