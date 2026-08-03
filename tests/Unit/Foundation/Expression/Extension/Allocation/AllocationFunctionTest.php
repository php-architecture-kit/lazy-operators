<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Allocation;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Allocation\AllocationFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Allocation\AllocationRemainderTarget;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Tests\Support\NumericSpyExpression;
use PHPUnit\Framework\TestCase;

final class AllocationFunctionTest extends TestCase
{
    /**
     * Every allocation, regardless of shares/precision/remainder target, must sum back to the
     * original amount (rounded to that precision) exactly - that is the whole point of this
     * class. Asserted here with a tiny delta rather than assertSame() because summing several
     * independently-rounded floats can land a few ULPs away from round($amount, $precision)
     * even though both print identically (e.g. 9.99 + 20 + 30 + 40 !== 99.99 in raw float terms).
     *
     * @param list<float> $result
     */
    private static function assertLossless(int|float $amount, int $precision, array $result, string $message = ''): void
    {
        self::assertEqualsWithDelta(round($amount, $precision), array_sum($result), 1e-9, $message);
    }

    public function testSplitsEvenlyWhenThereIsNoRemainder(): void
    {
        $function = new AllocationFunction(
            new NumericSpyExpression(30),
            new IntLiteral(0),
            AllocationRemainderTarget::First,
            new NumericSpyExpression(1),
            new NumericSpyExpression(1),
            new NumericSpyExpression(1),
        );

        $result = $function();

        self::assertSame([10.0, 10.0, 10.0], $result);
        self::assertLossless(30, 0, $result);
    }

    public function testFoldsTheRemainderIntoTheFirstShareByDefault(): void
    {
        $function = new AllocationFunction(
            new NumericSpyExpression(1),
            new IntLiteral(2),
            AllocationRemainderTarget::First,
            new NumericSpyExpression(1),
            new NumericSpyExpression(4),
            new NumericSpyExpression(1),
        );

        $result = $function();

        self::assertSame([0.16, 0.67, 0.17], $result);
        self::assertLossless(1, 2, $result);
    }

    public function testFoldsTheRemainderIntoTheLastShareWhenRequested(): void
    {
        $function = new AllocationFunction(
            new NumericSpyExpression(1),
            new IntLiteral(2),
            AllocationRemainderTarget::Last,
            new NumericSpyExpression(1),
            new NumericSpyExpression(4),
            new NumericSpyExpression(1),
        );

        $result = $function();

        self::assertSame([0.17, 0.67, 0.16], $result);
        self::assertLossless(1, 2, $result);
    }

    public function testFoldsTheRemainderIntoTheLargestShareWhenRequested(): void
    {
        $function = new AllocationFunction(
            new NumericSpyExpression(1),
            new IntLiteral(2),
            AllocationRemainderTarget::Largest,
            new NumericSpyExpression(1),
            new NumericSpyExpression(4),
            new NumericSpyExpression(1),
        );

        $result = $function();

        self::assertSame([0.17, 0.66, 0.17], $result);
        self::assertLossless(1, 2, $result);
    }

    public function testFoldsTheRemainderIntoTheSmallestShareWhenRequested(): void
    {
        $function = new AllocationFunction(
            new NumericSpyExpression(1),
            new IntLiteral(2),
            AllocationRemainderTarget::Smallest,
            new NumericSpyExpression(4),
            new NumericSpyExpression(1),
            new NumericSpyExpression(4),
        );

        $result = $function();

        self::assertSame([0.44, 0.12, 0.44], $result);
        self::assertLossless(1, 2, $result);
    }

    public function testAllocatesProportionallyToUnequalShares(): void
    {
        $function = new AllocationFunction(
            new NumericSpyExpression(100),
            new IntLiteral(2),
            AllocationRemainderTarget::First,
            new NumericSpyExpression(30),
            new NumericSpyExpression(70),
        );

        $result = $function();

        self::assertSame([30.0, 70.0], $result);
        self::assertLossless(100, 2, $result);
    }

    public function testWorksWithASingleShare(): void
    {
        $function = new AllocationFunction(
            new NumericSpyExpression(42.5),
            new IntLiteral(2),
            AllocationRemainderTarget::First,
            new NumericSpyExpression(1),
        );

        $result = $function();

        self::assertSame([42.5], $result);
        self::assertLossless(42.5, 2, $result);
    }

    public function testRemainsLosslessForANegativeAmount(): void
    {
        $function = new AllocationFunction(
            new NumericSpyExpression(-10),
            new IntLiteral(2),
            AllocationRemainderTarget::First,
            new NumericSpyExpression(1),
            new NumericSpyExpression(1),
            new NumericSpyExpression(1),
        );

        $result = $function();

        self::assertSame([-3.34, -3.33, -3.33], $result);
        self::assertLossless(-10, 2, $result);
    }

    /**
     * Systematically exercises the lossless invariant across every remainder target, a range of
     * precisions (including 0 and a higher precision than the other tests use), share counts from
     * a single share up to six, decimal (non-integer) share weights, and a share list that sums
     * to zero deliberately excluded (division by the share total is undefined there, same as
     * DivisionOperator's behaviour for other arithmetic in this library).
     */
    public function testEveryAllocationSumsBackToTheOriginalAmount(): void
    {
        $cases = [
            [10, [1, 1, 1], 2],
            [1, [1, 4, 1], 2],
            [100, [30, 70], 2],
            [99.99, [1, 2, 3, 4], 2],
            [7, [1], 0],
            [-10, [1, 1, 1], 2],
            [123.456, [1, 1], 3],
            [0.1, [1, 1, 1], 4],
            [1_000_000, [2, 3, 5, 7, 11, 13], 2],
            [50, [2.5, 1.5, 6], 2],
            [1, [1, 1, 1, 1, 1, 1], 0],
            [-99.99, [3, 7], 2],
        ];

        foreach ($cases as [$amount, $shares, $precision]) {
            foreach (AllocationRemainderTarget::cases() as $target) {
                $shareExpressions = array_map(static fn (int|float $share) => new NumericSpyExpression($share), $shares);
                $function = new AllocationFunction(
                    new NumericSpyExpression($amount),
                    new IntLiteral($precision),
                    $target,
                    ...$shareExpressions,
                );

                self::assertLossless(
                    $amount,
                    $precision,
                    $function(),
                    sprintf('Lossless check failed for amount=%s, precision=%d, target=%s', $amount, $precision, $target->name),
                );
            }
        }
    }

    public function testUseBcMathIfAvailableDefaultsToTrue(): void
    {
        $function = new AllocationFunction(
            new NumericSpyExpression(1),
            new IntLiteral(2),
            AllocationRemainderTarget::First,
            new NumericSpyExpression(1),
        );

        self::assertTrue($function->useBcMathIfAvailable);
    }

    /**
     * __invoke() picks bcmath or native floats based on useBcMathIfAvailable && function_exists('bcadd').
     * This dev/CI environment always has ext-bcmath loaded, so without the flag the native fallback path
     * (for environments where ext-bcmath isn't installed, which composer.json only ever "suggests", never
     * requires) would never actually run under test. Toggling useBcMathIfAvailable to false forces that
     * path directly, independently of what the environment provides, against the same case matrix as
     * testEveryAllocationSumsBackToTheOriginalAmount().
     */
    public function testBothTheBcMathAndNativeFloatStrategiesAreIndependentlyLossless(): void
    {
        $cases = [
            [10, [1, 1, 1], 2],
            [1, [1, 4, 1], 2],
            [100, [30, 70], 2],
            [99.99, [1, 2, 3, 4], 2],
            [7, [1], 0],
            [-10, [1, 1, 1], 2],
            [123.456, [1, 1], 3],
            [0.1, [1, 1, 1], 4],
            [1_000_000, [2, 3, 5, 7, 11, 13], 2],
            [50, [2.5, 1.5, 6], 2],
            [1, [1, 1, 1, 1, 1, 1], 0],
            [-99.99, [3, 7], 2],
        ];

        foreach ($cases as [$amount, $shares, $precision]) {
            foreach (AllocationRemainderTarget::cases() as $target) {
                $shareExpressions = array_map(static fn (int|float $share) => new NumericSpyExpression($share), $shares);
                $bcMathFunction = new AllocationFunction(
                    new NumericSpyExpression($amount),
                    new IntLiteral($precision),
                    $target,
                    ...$shareExpressions,
                );

                $shareExpressions = array_map(static fn (int|float $share) => new NumericSpyExpression($share), $shares);
                $nativeFloatFunction = new AllocationFunction(
                    new NumericSpyExpression($amount),
                    new IntLiteral($precision),
                    $target,
                    ...$shareExpressions,
                );
                $nativeFloatFunction->useBcMathIfAvailable = false;

                $context = sprintf('amount=%s, precision=%d, target=%s', $amount, $precision, $target->name);

                self::assertLossless($amount, $precision, $bcMathFunction(), "bcmath strategy: {$context}");
                self::assertLossless($amount, $precision, $nativeFloatFunction(), "native float strategy: {$context}");
            }
        }
    }
}
