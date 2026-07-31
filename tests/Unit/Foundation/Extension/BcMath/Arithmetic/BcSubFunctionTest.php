<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcSubFunction;
use PhpArchitecture\LazyOperators\Foundation\Static\StringLiteral;
use PHPUnit\Framework\TestCase;

final class BcSubFunctionTest extends TestCase
{
    public function testSubtractsToTheGivenScale(): void
    {
        $function = new BcSubFunction(new StringLiteral('5'), new StringLiteral('3'));

        self::assertSame(2, $function());
    }

    public function testSubtractsDecimalStringsToTheGivenScale(): void
    {
        $function = new BcSubFunction(new StringLiteral('10.5'), new StringLiteral('3.2'), 2);

        self::assertSame(7.3, $function());
        self::assertSame('7.30', (string) $function->bcValue());
    }
}
