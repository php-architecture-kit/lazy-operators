<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Conversion;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Conversion\OctDecFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class OctDecFunctionTest extends TestCase
{
    public function testComputesOctDecFunction(): void
    {
        $function = new OctDecFunction(new SpyExpression('17'));

        self::assertSame(15, $function());
    }
}
