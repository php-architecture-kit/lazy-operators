<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\CosFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class CosFunctionTest extends TestCase
{
    public function testComputesCosFunction(): void
    {
        $function = new CosFunction(new SpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(0.5403023058681398, $result, 1e-9);
    }
}
