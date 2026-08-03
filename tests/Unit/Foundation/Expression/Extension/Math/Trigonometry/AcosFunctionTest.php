<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\AcosFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class AcosFunctionTest extends TestCase
{
    public function testComputesAcosFunction(): void
    {
        $function = new AcosFunction(new NumericSpyExpression(0.5));

        $result = $function();

        self::assertEqualsWithDelta(1.0471975511965979, $result, 1e-9);
    }
}
