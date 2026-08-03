<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\SinhFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class SinhFunctionTest extends TestCase
{
    public function testComputesSinhFunction(): void
    {
        $function = new SinhFunction(new NumericSpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(1.1752011936438014, $result, 1e-9);
    }
}
