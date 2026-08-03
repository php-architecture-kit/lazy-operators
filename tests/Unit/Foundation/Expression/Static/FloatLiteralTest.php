<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Static\FloatLiteral;
use PHPUnit\Framework\TestCase;

final class FloatLiteralTest extends TestCase
{
    public function testWrapsAFloat(): void
    {
        $value = new FloatLiteral(4.2);

        self::assertSame(4.2, $value());
    }
}
