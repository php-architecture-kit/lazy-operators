<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support;

trait GetScaleFunction
{
    private function getScale(string $number): int
    {
        $dotPos = strrpos($number, '.');
        if (false === $dotPos) {
            return 0;
        }

        return strlen(substr($number, $dotPos + 1));
    }
}
