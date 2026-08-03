<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\Atan2Function;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class Atan2FunctionTest extends TestCase
{
    public function testComputesAtan2Function(): void
    {
        $function = new Atan2Function(new NumericSpyExpression(1.0), new NumericSpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(0.7853981633974483, $result, 1e-9);
    }
}
