<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Numeric;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\AbsFunction;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;

final class AbsFunctionTest extends TestCase
{
    public function testComputesAbsFunction(): void
    {
        $function = new AbsFunction(new NumericSpyExpression(-5));

        self::assertSame(5, $function());
    }
}
