<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Allocation;

use PhpArchitecture\LazyOperators\Foundation\Allocation\Allocation;
use PhpArchitecture\LazyOperators\Foundation\Allocation\AllocationRemainderTarget;
use PhpArchitecture\LazyOperators\Foundation\Allocation\Exception\EmptySharesException;
use PHPUnit\Framework\TestCase;

final class AllocationTest extends TestCase
{
    public function testAllocatesRawScalarsProportionally(): void
    {
        $expression = Allocation::allocate(100, [30, 70], 2);
        $result = $expression();

        self::assertSame([30.0, 70.0], $result);
        self::assertEqualsWithDelta(100.0, array_sum($result), 1e-9);
    }

    public function testUsesTheGivenRemainderTargetPolicy(): void
    {
        $expression = Allocation::allocate(1, [1, 4, 1], 2, AllocationRemainderTarget::Last);
        $result = $expression();

        self::assertSame([0.17, 0.67, 0.16], $result);
        self::assertEqualsWithDelta(1.0, array_sum($result), 1e-9);
    }

    public function testThrowsWhenNoSharesAreGiven(): void
    {
        $this->expectException(EmptySharesException::class);
        $this->expectExceptionMessage('At least one share is required to allocate an amount.');

        Allocation::allocate(100, [], 2);
    }
}
