<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Static\BoolLiteral;
use PHPUnit\Framework\TestCase;

final class BoolLiteralTest extends TestCase
{
    public function testWrapsABoolean(): void
    {
        $value = new BoolLiteral(true);

        self::assertTrue($value());
    }
}
