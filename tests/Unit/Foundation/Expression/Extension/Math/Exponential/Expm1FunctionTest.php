<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Exponential;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Exponential\Expm1Function;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class Expm1FunctionTest extends TestCase
{
    public function testComputesExpm1Function(): void
    {
        $function = new Expm1Function(new NumericSpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(1.718281828459045, $result, 1e-9);
    }
}
