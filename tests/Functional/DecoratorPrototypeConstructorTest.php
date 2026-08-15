<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Functional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\Arithmetic;
use PhpArchitecture\LazyOperators\Foundation\Expression\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Tests\Support\ChannelDecorator;
use PhpArchitecture\LazyOperators\Tests\Support\RequiredTagDecorator;
use PHPUnit\Framework\TestCase;

/**
 * The tests that were red in #8, now driven through `ExpressionTreeConfig::decoratedBy()`.
 *
 * `DecoratesNodes::decorate()` used to read only the *class* of the Decorator handed to
 * `ExpressionTreeConfig` and build a fresh instance per node, which made the caller-supplied
 * instance a prototype: it existed to name a class and was discarded. That forced a throwaway
 * `Expression` argument (the surface complaint in #4) and, less visibly, left a decorator no
 * route to any dependency besides the node it wraps.
 *
 * It now calls a `Closure(Expression): Decorator` instead, so the caller constructs the
 * decorator themselves, with whatever it needs, at the one moment the node is known.
 *
 * The last two tests still use the prototype form, unchanged from #8: it keeps working exactly
 * as it did, including its costs.
 */
final class DecoratorPrototypeConstructorTest extends TestCase
{
    protected function setUp(): void
    {
        ChannelDecorator::reset();
        RequiredTagDecorator::reset();
    }

    /**
     * Was red in #8: the configured channel was silently replaced by the constructor default,
     * with no exception, no warning and nothing PHPStan could see.
     */
    public function testDecoratorKeepsTheDependencyItWasConstructedWith(): void
    {
        $config = ExpressionTreeConfig::decoratedBy(
            static fn (Expression $node): Decorator => new ChannelDecorator($node, 'audit'),
        );

        $expr = Arithmetic::of(2, $config)->add(3)->build();

        self::assertSame(5, $expr());
        self::assertSame(['audit', 'audit', 'audit'], ChannelDecorator::$channels);
    }

    /**
     * Was red in #8: a dependency with no default could not be used at all — it failed at build
     * time with an ArgumentCountError raised from inside the library, at a call site the caller
     * does not own. `Decorator` is an interface and cannot declare a constructor, so "the
     * constructor takes exactly one Expression" was a convention no type could express; passing
     * a factory removes the need to express it.
     */
    public function testDecoratorWithARequiredDependencyIsUsable(): void
    {
        $config = ExpressionTreeConfig::decoratedBy(
            static fn (Expression $node): Decorator => new RequiredTagDecorator($node, 'pricing'),
        );

        $expr = Arithmetic::of(2, $config)->add(3)->build();

        self::assertSame(5, $expr());
        self::assertSame(['pricing', 'pricing', 'pricing'], RequiredTagDecorator::$tags);
    }

    /**
     * The thing the static-handle workaround could not do. #8's
     * testStaticHandleIsTheOnlyRouteToAPerTreeDependency documented that reaching a real
     * collaborator meant a process-global handle, which two trees needing two different channels
     * cannot share. Each factory closes over its own, so they coexist.
     */
    public function testTwoTreesCanCarryDifferentDependenciesAtTheSameTime(): void
    {
        $audit = ExpressionTreeConfig::decoratedBy(
            static fn (Expression $node): Decorator => new ChannelDecorator($node, 'audit'),
        );
        $debug = ExpressionTreeConfig::decoratedBy(
            static fn (Expression $node): Decorator => new ChannelDecorator($node, 'debug'),
        );

        $audited = Arithmetic::of(2, $audit)->add(3)->build();
        $debugged = Arithmetic::of(4, $debug)->add(1)->build();

        self::assertSame(5, $audited());
        self::assertSame(5, $debugged());
        self::assertSame(
            ['audit', 'audit', 'audit', 'debug', 'debug', 'debug'],
            ChannelDecorator::$channels,
        );
    }

    /**
     * BC, unchanged from #8. The prototype form still builds a fresh instance per node from the
     * prototype's class, so it still constructs the class once more than there are nodes and
     * still never evaluates the prototype itself. Existing callers are unaffected — they simply
     * keep paying for what the factory form no longer costs.
     */
    public function testPrototypeIsConstructedOnceMoreThanThereAreNodesAndIsNeverEvaluated(): void
    {
        $config = new ExpressionTreeConfig(new ChannelDecorator(new IntLiteral(99)));

        self::assertSame(1, ChannelDecorator::$constructions, 'the caller-written prototype');
        self::assertSame(0, ChannelDecorator::$invocations);

        $expr = Arithmetic::of(2, $config)->add(3)->build();

        self::assertSame(4, ChannelDecorator::$constructions, '1 prototype + 3 nodes');
        self::assertSame(0, ChannelDecorator::$invocations, 'building evaluates nothing');

        self::assertSame(5, $expr());

        self::assertSame(4, ChannelDecorator::$constructions);
        self::assertSame(3, ChannelDecorator::$invocations, 'the 3 nodes only, never the prototype');
        self::assertNotContains(99, ChannelDecorator::$channels);
    }

    /**
     * BC, unchanged from #8. The prototype form still cannot carry a dependency — the fresh
     * per-node instances still get the constructor default. This is now a choice the caller makes
     * by using the older form, not the only behaviour available.
     */
    public function testPrototypeFormStillDropsItsDependencyExactlyAsBefore(): void
    {
        $config = new ExpressionTreeConfig(new ChannelDecorator(new IntLiteral(0), 'audit'));

        $expr = Arithmetic::of(2, $config)->add(3)->build();

        self::assertSame(5, $expr());
        self::assertSame(
            [ChannelDecorator::DEFAULT_CHANNEL, ChannelDecorator::DEFAULT_CHANNEL, ChannelDecorator::DEFAULT_CHANNEL],
            ChannelDecorator::$channels,
        );
    }
}
