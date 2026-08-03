<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Conversion;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Conversion\OctDecFunction;
use PhpArchitecture\LazyOperators\Tests\Support\StringSpyExpression;

final class OctDecFunctionTest extends TestCase
{
    public function testComputesOctDecFunction(): void
    {
        $function = new OctDecFunction(new StringSpyExpression('17'));

        self::assertSame(15, $function());
    }
}
