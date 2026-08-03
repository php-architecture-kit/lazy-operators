<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Conversion;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\HexDecFunction;
use PhpArchitecture\LazyOperators\Tests\Support\StringSpyExpression;

final class HexDecFunctionTest extends TestCase
{
    public function testComputesHexDecFunction(): void
    {
        $function = new HexDecFunction(new StringSpyExpression('ff'));

        self::assertSame(255, $function());
    }
}
