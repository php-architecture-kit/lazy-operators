<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Functional;

use PhpArchitecture\LazyOperators\Foundation\Extension\Allocation\Allocation;
use PhpArchitecture\LazyOperators\Foundation\Extension\Allocation\AllocationRemainderTarget;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\BcMath;
use PHPUnit\Framework\TestCase;

/**
 * Foundation\Extension\Allocation\AllocationFunction is a plain NumberValue consumer: it invokes its amount/shares/
 * precision operands via NumberValue::__invoke(): int|float, so any Foundation\Extension\BcMath node
 * (PrecisionNumberValue extends NumberValue) can be handed to it directly as an amount, a share, or the
 * precision itself. These tests exercise that mix — precise decimal sources feeding the existing
 * float-based allocator — across various amounts/precisions/share counts/remainder targets, and assert
 * the pieces always sum back to the declared amount.
 */
final class BcMathAllocationIntegrationTest extends TestCase
{
    public function testBcMathComputedAmountAvoidsTheClassicFloatAdditionBugAndSumsMatch(): void
    {
        // Native 0.1 + 0.2 === 0.30000000000000004; BcMath::add computes an exact "0.3" instead.
        $amount = BcMath::add('0.1', '0.2', 1);

        $result = Allocation::allocate($amount, [1, 1], 2)();

        self::assertSame([0.15, 0.15], $result);
        $this->assertSumMatchesAmount(0.3, $result, 2);
    }

    public function testAllocatingIntoThirdsUsingBcMathComputedSharesSumsBackToTheAmount(): void
    {
        $third = BcMath::div('1', '3', 10);

        $result = Allocation::allocate(100, [$third, $third, $third], 2)();

        self::assertSame([33.34, 33.33, 33.33], $result);
        $this->assertSumMatchesAmount(100, $result, 2);
    }

    public function testAllocationAcceptsWeightedSharesComputedViaBcMath(): void
    {
        $twentyPercent = BcMath::mul('100', '0.2', 4);
        $thirtyPercent = BcMath::mul('100', '0.3', 4);
        $fiftyPercent = BcMath::mul('100', '0.5', 4);

        $result = Allocation::allocate(99.97, [$twentyPercent, $thirtyPercent, $fiftyPercent], 2, AllocationRemainderTarget::Largest)();

        self::assertSame([19.99, 29.99, 49.99], $result);
        $this->assertSumMatchesAmount(99.97, $result, 2);
    }

    public function testAllocationAcceptsAPrecisionArgumentComputedViaBcMath(): void
    {
        $precision = BcMath::add('1', '1', 0); // 2, computed rather than a literal int

        $result = Allocation::allocate(7, [1, 3, 2], $precision, AllocationRemainderTarget::Smallest)();

        self::assertSame([1.17, 3.5, 2.33], $result);
        $this->assertSumMatchesAmount(7, $result, 2);
    }

    public function testRemainderTargetsAllProduceASumMatchingTheBcMathComputedAmount(): void
    {
        $amount = BcMath::add('10', '0.55', 2);

        $expectedByTarget = [
            AllocationRemainderTarget::First->name => [1.49, 1.51, 1.51, 1.51, 1.51, 1.51, 1.51],
            AllocationRemainderTarget::Largest->name => [1.49, 1.51, 1.51, 1.51, 1.51, 1.51, 1.51],
            AllocationRemainderTarget::Smallest->name => [1.49, 1.51, 1.51, 1.51, 1.51, 1.51, 1.51],
            AllocationRemainderTarget::Last->name => [1.51, 1.51, 1.51, 1.51, 1.51, 1.51, 1.49],
        ];

        foreach (AllocationRemainderTarget::cases() as $target) {
            $result = Allocation::allocate($amount, [1, 1, 1, 1, 1, 1, 1], 2, $target)();

            self::assertSame($expectedByTarget[$target->name], $result, "Mismatch for remainder target {$target->name}");
            $this->assertSumMatchesAmount(10.55, $result, 2);
        }
    }

    public function testFloatingPointDriftInTheRawSumIsResolvedByRoundingToTheDeclaredPrecision(): void
    {
        // AllocationFunction's own float arithmetic can leave sub-cent noise in the raw array_sum()
        // (10.55 split 7 ways lands on 10.549999999999999, not 10.55) — the invariant that actually
        // matters is that the sum matches once rounded to the declared precision, which it does here
        // both with and without a BcMath-computed amount.
        $result = Allocation::allocate(10.55, [1, 1, 1, 1, 1, 1, 1], 2)();

        self::assertNotSame(10.55, array_sum($result));
        $this->assertSumMatchesAmount(10.55, $result, 2);

        $bcMathResult = Allocation::allocate(BcMath::add('10.55', '0', 2), [1, 1, 1, 1, 1, 1, 1], 2)();

        $this->assertSumMatchesAmount(10.55, $bcMathResult, 2);
    }

    /**
     * @param list<float> $result
     */
    private function assertSumMatchesAmount(int|float $amount, array $result, int $precision): void
    {
        self::assertSame(round($amount, $precision), round(array_sum($result), $precision));
    }
}
