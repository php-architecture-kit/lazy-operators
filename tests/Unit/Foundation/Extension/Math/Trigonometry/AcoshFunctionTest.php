<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AcoshFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class AcoshFunctionTest extends TestCase
{
    public function testComputesAcoshFunction(): void
    {
        $function = new AcoshFunction(new SpyExpression(2.0));

        $result = $function();

        self::assertEqualsWithDelta(1.3169578969248166, $result, 1e-9);
    }
}
