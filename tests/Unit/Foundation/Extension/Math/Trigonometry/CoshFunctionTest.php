<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Extension\Math\Trigonometry;

use PHPUnit\Framework\TestCase;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Trigonometry\CoshFunction;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;

final class CoshFunctionTest extends TestCase
{
    public function testComputesCoshFunction(): void
    {
        $function = new CoshFunction(new SpyExpression(1.0));

        $result = $function();

        self::assertEqualsWithDelta(1.5430806348152437, $result, 1e-9);
    }
}
