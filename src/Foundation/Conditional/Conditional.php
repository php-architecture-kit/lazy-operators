<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;

class Conditional
{
    public static function if(mixed $condition, ?PipelineConfig $config = null): IfBuilder
    {
        return IfBuilder::of($condition, $config);
    }

    public static function switch(mixed $subject, ?PipelineConfig $config = null): SwitchBuilder
    {
        return SwitchBuilder::of($subject, $config);
    }
}
