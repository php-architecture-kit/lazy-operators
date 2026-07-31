<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Type\BooleanValue;

class Conditional
{
    public static function if(bool|(Expression&BooleanValue) $condition, ?PipelineConfig $config = null): IfBuilder
    {
        return IfBuilder::of($condition, $config);
    }

    public static function switch(mixed $subject, ?PipelineConfig $config = null): SwitchBuilder
    {
        return SwitchBuilder::of($subject, $config);
    }
}
