<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\CosFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class CosFunctionTest extends TestCase
{
    public function testComputesCosFunction(): void
    {
        $function = new CosFunction(new NumericSpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(0.5403023058681398, $result, 1e-9);
    }
}
