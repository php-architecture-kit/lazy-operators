<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Functional;

use Monolog\Handler\TestHandler;
use Monolog\Logger;
use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\Arithmetic;
use PhpArchitecture\LazyOperators\Foundation\Expression\Cast\BooleanCast;
use PhpArchitecture\LazyOperators\Foundation\Expression\Cast\FloatCast;
use PhpArchitecture\LazyOperators\Foundation\Expression\Cast\IntegerCast;
use PhpArchitecture\LazyOperators\Foundation\Expression\Comparator\Comparator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Conditional;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Logical\Logical;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Tests\Support\LoggerDecorator;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * PR #5 (github.com/php-architecture-kit/lazy-operators/pull/5) originally proposed narrowing
 * both Comparator::spaceship() and Conditional::if()/switch() so their output stays typed after
 * decoration, avoiding an explicit Cast. Review feedback rejected doing this for Conditional as
 * excessive for the library's responsibility ("to nie jest zadanie biblioteki... jego
 * obowiązkiem jest dostarczyć cast do typu") and was later confirmed as final for both the
 * decorated and undecorated case: Conditional if()/switch() results MUST always be Cast
 * explicitly by the caller before they can feed Arithmetic/Logical, by design.
 *
 * PR #5 was reworked to fix only the part that was an actual regression: decorating an
 * already-narrowly-typed node (e.g. SpaceshipOperator, which unconditionally implements
 * IntegerValue) used to silently downgrade it to a bare Expression the moment ANY Decorator got
 * configured, even though an undecorated spaceship() result never had that problem. Conditional
 * never had a narrower type to lose in the first place — decorated or not, it stays generic.
 *
 * This test class plugs in the single most obvious real-world Decorator (LoggerDecorator — logs
 * every stage, nothing fancier) and drives it the way a caller naturally would: build a
 * Comparator/Conditional result under a decorated config, then feed it straight into
 * Arithmetic/Logical. It documents both confirmed outcomes:
 * - Comparator::spaceship() now works under decoration without a Cast (the fix).
 * - Conditional::if()/switch() still require an explicit Cast under decoration — confirmed as
 *   permanent, expected behavior, not a bug to track.
 */
final class LoggerDecoratorTypeNarrowingGapTest extends TestCase
{
    private ExpressionTreeConfig $config;

    protected function setUp(): void
    {
        $handler = new TestHandler();
        $logger = new Logger('lazy-operators');
        $logger->pushHandler($handler);
        LoggerDecorator::useLogger($logger);

        $this->config = new ExpressionTreeConfig(new LoggerDecorator(new IntLiteral(0)));
    }

    public function testDecoratedComparatorSpaceshipResultIsUsableByArithmeticWithoutAnExplicitCast(): void
    {
        // Regression guard: DecoratesNodes::decorate() re-exposes the decorated result using
        // whichever typed interface the undecorated SpaceshipOperator already had (IntegerValue),
        // so this works the same decorated as undecorated — no Cast needed.
        $rank = Comparator::of(5, $this->config)->spaceship(3)->build();

        $expr = Arithmetic::of(10, $this->config)->add($rank)->build();

        self::assertSame(11, $expr());
    }

    public function testExplicitIntegerCastOnADecoratedSpaceshipResultIsHarmlessButUnnecessary(): void
    {
        $rank = Comparator::of(5, $this->config)->spaceship(3)->build();

        $expr = Arithmetic::of(10, $this->config)->add(new IntegerCast($rank))->build();

        self::assertSame(11, $expr());
    }

    public function testDecoratedConditionalIfResultRequiresAnExplicitCastToBeFedIntoArithmeticByDesign(): void
    {
        $bonus = Conditional::if(true, $this->config)->then(5)->else(0)->build();

        // Confirmed as permanent, by design: IfElseOperator only ever implements the generic
        // Expression contract (branches may differ in type), decorated or not, so this always
        // needs a Cast — see the class docblock and DecoratesNodes::decorate().
        $this->expectException(TypeError::class);

        Arithmetic::of(100, $this->config)->add($bonus)->build();
    }

    public function testExplicitFloatCastRestoresCompatibilityAfterDecoratingAConditionalIfResult(): void
    {
        $bonus = Conditional::if(true, $this->config)->then(5)->else(0)->build();

        $expr = Arithmetic::of(100, $this->config)->add(new FloatCast($bonus))->build();

        self::assertSame(105.0, $expr());
    }

    public function testDecoratedConditionalSwitchResultRequiresAnExplicitCastToBeFedIntoLogicalByDesign(): void
    {
        $flag = Conditional::switch(2, $this->config)
            ->case(1, false)
            ->case(2, true)
            ->default(false)
            ->build();

        // Same confirmed-by-design boundary as the If case above, for SwitchCaseOperator.
        $this->expectException(TypeError::class);

        Logical::of(true, $this->config)->and($flag)->build();
    }

    public function testExplicitBooleanCastRestoresCompatibilityAfterDecoratingAConditionalSwitchResult(): void
    {
        $flag = Conditional::switch(2, $this->config)
            ->case(1, false)
            ->case(2, true)
            ->default(false)
            ->build();

        $expr = Logical::of(true, $this->config)->and(new BooleanCast($flag))->build();

        self::assertTrue($expr());
    }
}
