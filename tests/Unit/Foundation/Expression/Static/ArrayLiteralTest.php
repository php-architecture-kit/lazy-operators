<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Static;

use PhpArchitecture\LazyOperators\Foundation\Expression\Static\ArrayLiteral;
use PHPUnit\Framework\TestCase;

final class ArrayLiteralTest extends TestCase
{
    public function testWrapsAnArray(): void
    {
        $array = ['a', 'b'];
        $value = new ArrayLiteral($array);

        self::assertSame($array, $value());
    }
}
