<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Exponential;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\Log10Function;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class Log10FunctionTest extends TestCase
{
    public function testComputesLog10Function(): void
    {
        $function = new Log10Function(new SpyExpression(100.0));

        $result = $function();

        self::assertEqualsWithDelta(2.0, $result, 1e-9);
    }
}
