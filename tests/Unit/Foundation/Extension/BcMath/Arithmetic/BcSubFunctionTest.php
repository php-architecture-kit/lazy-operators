<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcSubFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PHPUnit\Framework\TestCase;

final class BcSubFunctionTest extends TestCase
{
    public function testSubtractsToTheGivenScale(): void
    {
        $function = new BcSubFunction(new IntLiteral(5), new IntLiteral(3));

        self::assertSame(2, $function());
    }

    public function testSubtractsDecimalStringsToTheGivenScale(): void
    {
        $function = new BcSubFunction(new FloatLiteral(10.5), new FloatLiteral(3.2), new IntLiteral(2));

        self::assertSame(7.3, $function());
        self::assertSame('7.30', (string) $function->bcValue());
    }
}
