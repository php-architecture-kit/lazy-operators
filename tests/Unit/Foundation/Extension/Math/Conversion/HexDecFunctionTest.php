<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Conversion;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\HexDecFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class HexDecFunctionTest extends TestCase
{
    public function testComputesHexDecFunction(): void
    {
        $function = new HexDecFunction(new SpyExpression('ff'));

        self::assertSame(255, $function());
    }
}
