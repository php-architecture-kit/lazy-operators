<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\AtanhFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class AtanhFunctionTest extends TestCase
{
    public function testComputesAtanhFunction(): void
    {
        $function = new AtanhFunction(new SpyExpression(0.5));

        $result = $function();

        self::assertEqualsWithDelta(0.5493061443340548, $result, 1e-9);
    }
}
