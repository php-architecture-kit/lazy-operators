<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Static;

use PhpArchitecture\LazyOperators\Foundation\Expression\Static\BoolLiteral;
use PHPUnit\Framework\TestCase;

final class BoolLiteralTest extends TestCase
{
    public function testWrapsABoolean(): void
    {
        $value = new BoolLiteral(true);

        self::assertTrue($value());
    }
}
