<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Math\Trigonometry\PiFunction;

final class PiFunctionTest extends TestCase
{
    public function testComputesPiFunction(): void
    {
        $function = new PiFunction();

        self::assertEqualsWithDelta(3.141592653589793, $function(), 1e-9);
    }
}
