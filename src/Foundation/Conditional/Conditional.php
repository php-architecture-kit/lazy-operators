<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Conditional;

class Conditional
{
    public static function if(mixed $condition): IfBuilder
    {
        return IfBuilder::of($condition);
    }

    public static function switch(mixed $subject): SwitchBuilder
    {
        return SwitchBuilder::of($subject);
    }
}
