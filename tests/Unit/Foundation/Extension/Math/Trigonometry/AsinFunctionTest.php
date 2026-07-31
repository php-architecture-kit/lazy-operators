<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AsinFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class AsinFunctionTest extends TestCase
{
    public function testComputesAsinFunction(): void
    {
        $function = new AsinFunction(new NumericSpyExpression(0.5));

        $result = $function();

        self::assertEqualsWithDelta(0.5235987755982989, $result, 1e-9);
    }
}
