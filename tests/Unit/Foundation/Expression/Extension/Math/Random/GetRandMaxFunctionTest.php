<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Random\GetRandMaxFunction;
use PHPUnit\Framework\TestCase;

final class GetRandMaxFunctionTest extends TestCase
{
    public function testReturnsTheSameValueAsTheNativeFunction(): void
    {
        $function = new GetRandMaxFunction();

        self::assertSame(getrandmax(), $function());
    }
}
