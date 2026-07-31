<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PHPUnit\Framework\TestCase;

final class IntLiteralTest extends TestCase
{
    public function testWrapsAnInteger(): void
    {
        $value = new IntLiteral(42);

        self::assertSame(42, $value());
    }
}
