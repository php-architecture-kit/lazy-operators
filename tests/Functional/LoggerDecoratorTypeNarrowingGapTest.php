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

/**
 * PR #5 (github.com/php-architecture-kit/lazy-operators/pull/5) proposed narrowing
 * Comparator/Conditional builders so their output stays typed after decoration, to avoid an
 * explicit Cast. The reviewer's pushback: "Proponowane rozwiązania są nadmiarowe względem
 * odpowiedzialności biblioteki" (the proposed solutions are excessive relative to the library's
 * responsibility) — i.e. requiring the caller to Cast is fine, the library shouldn't grow
 * per-type Operator subclasses just to avoid it.
 *
 * This test class plugs in the single most obvious real-world Decorator (LoggerDecorator — logs
 * every stage, nothing fancier) against *current master* (without PR #5's fix) and drives it the
 * way a caller naturally would: build a Comparator/Conditional result under a decorated config,
 * then feed it straight into Arithmetic/Logical, the same as an undecorated result already could
 * be. The tests below that don't apply an explicit Cast are EXPECTED TO ERROR (a real, uncaught
 * TypeError, not an expectException() assertion) — that's the point: this is what "the caller
 * casts it themselves" looks like in practice the moment ANY decorator is configured, not just a
 * contrived one. The paired *WithExplicitCast tests show the reviewer-endorsed workaround, and
 * pass.
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

    public function testDecoratedComparatorSpaceshipResultCannotBeFedIntoArithmeticWithoutAnExplicitCast(): void
    {
        $rank = Comparator::of(5, $this->config)->spaceship(3)->build();

        // Naive usage: an undecorated spaceship() result could be handed to Arithmetic::add()
        // directly (SpaceshipOperator always implements IntegerValue). Under a decorated config,
        // build() hands back the generic Decorator instead, so this is a real TypeError.
        $expr = Arithmetic::of(10, $this->config)->add($rank)->build();

        self::assertSame(11, $expr());
    }

    public function testExplicitIntegerCastRestoresCompatibilityAfterDecoratingASpaceshipResult(): void
    {
        $rank = Comparator::of(5, $this->config)->spaceship(3)->build();

        $expr = Arithmetic::of(10, $this->config)->add(new IntegerCast($rank))->build();

        self::assertSame(11, $expr());
    }

    public function testDecoratedConditionalIfResultCannotBeFedIntoArithmeticWithoutAnExplicitCast(): void
    {
        $bonus = Conditional::if(true, $this->config)->then(5)->else(0)->build();

        // Naive usage again: both branches are NumberValue, so an undecorated if() result could
        // be handed to Arithmetic::add() directly. Decorated, build() falls back to the generic
        // Expression contract — a real TypeError, not a contrived one.
        $expr = Arithmetic::of(100, $this->config)->add($bonus)->build();

        self::assertSame(105, $expr());
    }

    public function testExplicitFloatCastRestoresCompatibilityAfterDecoratingAConditionalIfResult(): void
    {
        $bonus = Conditional::if(true, $this->config)->then(5)->else(0)->build();

        $expr = Arithmetic::of(100, $this->config)->add(new FloatCast($bonus))->build();

        self::assertSame(105.0, $expr());
    }

    public function testDecoratedConditionalSwitchResultCannotBeFedIntoLogicalWithoutAnExplicitCast(): void
    {
        $flag = Conditional::switch(2, $this->config)
            ->case(1, false)
            ->case(2, true)
            ->default(false)
            ->build();

        // Both matched-value slots (and default) are BooleanValue, so an undecorated switch()
        // result could feed Logical::and() directly. Decorated, it can't — real TypeError.
        $expr = Logical::of(true, $this->config)->and($flag)->build();

        self::assertTrue($expr());
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
