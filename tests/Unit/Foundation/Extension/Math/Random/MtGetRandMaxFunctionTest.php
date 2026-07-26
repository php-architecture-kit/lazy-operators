<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtGetRandMaxFunction;
use PHPUnit\Framework\TestCase;

final class MtGetRandMaxFunctionTest extends TestCase
{
    public function testReturnsTheSameValueAsTheNativeFunction(): void
    {
        $function = new MtGetRandMaxFunction();

        self::assertSame(mt_getrandmax(), $function());
    }
}
