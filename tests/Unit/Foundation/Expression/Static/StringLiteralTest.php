<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Static;

use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PHPUnit\Framework\TestCase;

final class StringLiteralTest extends TestCase
{
    public function testWrapsAString(): void
    {
        $value = new StringLiteral('hello');

        self::assertSame('hello', $value());
    }
}
