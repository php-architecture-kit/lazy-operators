<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Functional;

use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\Arithmetic;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Tests\Support\ChannelDecorator;
use PhpArchitecture\LazyOperators\Tests\Support\RequiredTagDecorator;
use PHPUnit\Framework\TestCase;

/**
 * Evidence for #4, at the level a library consumer actually experiences it.
 *
 * `DecoratesNodes::decorate()` reads only the *class* of the Decorator handed to
 * `ExpressionTreeConfig` and builds a fresh instance per node (`new ($config->decorator::class)($node)`).
 * The instance the caller constructed is therefore a prototype: it exists to name a class, and is
 * discarded. That forces the caller to invent a throwaway `Expression` for it — the surface
 * complaint in #4 — but the throwaway argument is only the visible half of the problem. These
 * tests drive the other half: what happens to a decorator that carries anything *besides* the
 * node it wraps.
 *
 * Two tests here are intentionally left red — a genuine assertion failure and a genuine uncaught
 * error, not `expectException()` wrappers — so the actual output is visible in CI rather than
 * described. The two green tests characterise the current mechanism and the workaround it forces.
 */
final class DecoratorPrototypeConstructorTest extends TestCase
{
    protected function setUp(): void
    {
        ChannelDecorator::reset();
        RequiredTagDecorator::reset();
    }

    /**
     * RED. A decorator constructed with a dependency does not keep it.
     *
     * The caller writes the channel once, at the only place the API offers, and every node is
     * nevertheless decorated with the constructor *default* — the per-node re-instantiation
     * passes the node and nothing else. No exception, no warning, no static-analysis error: the
     * configured value is simply gone, and the tree evaluates happily with the wrong one.
     */
    public function testDecoratorKeepsTheDependencyItWasConstructedWith(): void
    {
        $config = new ExpressionTreeConfig(new ChannelDecorator(new IntLiteral(0), 'audit'));

        $expr = Arithmetic::of(2, $config)->add(3)->build();

        self::assertSame(5, $expr());
        self::assertSame(['audit', 'audit', 'audit'], ChannelDecorator::$channels);
    }

    /**
     * RED. A decorator whose dependency has no default cannot be used at all.
     *
     * `RequiredTagDecorator` is a legitimate implementation of `Decorator` — the interface cannot
     * declare a constructor, so nothing rejects it statically. It fails at build time with an
     * `ArgumentCountError` raised from inside the library (`DecoratesNodes` line 35), pointing at
     * a call site the caller does not own and cannot fix.
     */
    public function testDecoratorWithARequiredDependencyIsUsable(): void
    {
        $config = new ExpressionTreeConfig(new RequiredTagDecorator(new IntLiteral(0), 'pricing'));

        $expr = Arithmetic::of(2, $config)->add(3)->build();

        self::assertSame(5, $expr());
        self::assertSame(['pricing', 'pricing', 'pricing'], RequiredTagDecorator::$tags);
    }

    /**
     * GREEN, characterisation. Shows precisely what the prototype instance is worth.
     *
     * `Arithmetic::of(2)->add(3)` builds three nodes (two `IntLiteral`s and one
     * `AdditionOperator`), so the decorator class is constructed four times: once for the
     * prototype the caller wrote, three times for the nodes. The prototype is never evaluated —
     * the `Expression` it was handed is unreachable from the moment `decorate()` runs.
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
     * GREEN, characterisation. The workaround the mechanism forces, and its cost.
     *
     * Because per-node instances cannot receive anything, the only route to a real collaborator
     * is a static handle set once before the tree is built. `tests/Support/LoggerDecorator.php`
     * already does exactly this to reach a Monolog instance, and says so in its docblock — the
     * package's own test double had to be written around this constraint. It works, at the price
     * of making every decorator process-global and its configuration non-nestable: two trees
     * needing two different channels cannot coexist.
     */
    public function testStaticHandleIsTheOnlyRouteToAPerTreeDependency(): void
    {
        $config = new ExpressionTreeConfig(new ChannelDecorator(new IntLiteral(0)));

        $expr = Arithmetic::of(2, $config)->add(3)->build();

        self::assertSame(5, $expr());
        self::assertSame(
            [ChannelDecorator::DEFAULT_CHANNEL, ChannelDecorator::DEFAULT_CHANNEL, ChannelDecorator::DEFAULT_CHANNEL],
            ChannelDecorator::$channels,
        );
    }
}
