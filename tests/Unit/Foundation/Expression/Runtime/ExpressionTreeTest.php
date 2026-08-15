<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Runtime;

use PhpArchitecture\LazyOperators\Foundation\Expression\Arithmetic\Arithmetic;
use PhpArchitecture\LazyOperators\Foundation\Expression\Cast\FloatCast;
use PhpArchitecture\LazyOperators\Foundation\Expression\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Exception\PortNotBoundException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Exception\UnknownExpressionTreeInputException;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTree;
use PhpArchitecture\LazyOperators\Foundation\Expression\Port;
use PhpArchitecture\LazyOperators\Foundation\Expression\Ports;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\FloatLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PHPUnit\Framework\TestCase;

final class ExpressionTreeTest extends TestCase
{
    public function testInputNamesReflectsTheDeclaredInputMap(): void
    {
        $left = new Port('left');
        $right = new Port('right');
        $tree = new ExpressionTree(
            new CallbackOperator(static fn (int $a, int $b): int => $a + $b, $left, $right),
            ['left' => $left, 'right' => $right],
        );

        self::assertSame(['left', 'right'], $tree->inputNames());
    }

    public function testBindWiresTheNamedPortAndReturnsTheSameTree(): void
    {
        $left = new Port('left');
        $right = new Port('right');
        $tree = new ExpressionTree(
            new CallbackOperator(static fn (int $a, int $b): int => $a + $b, $left, $right),
            ['left' => $left, 'right' => $right],
        );

        $bound = $tree->bind('left', new IntLiteral(2))->bind('right', new IntLiteral(3));

        self::assertSame($tree, $bound);
        self::assertSame(5, $tree());
    }

    public function testBindingAnUnknownNameThrows(): void
    {
        $port = new Port('left');
        $tree = new ExpressionTree($port, ['left' => $port]);

        $this->expectException(UnknownExpressionTreeInputException::class);

        $tree->bind('missing', new IntLiteral(1));
    }

    public function testInvokingBeforeBindingStillThrowsPortNotBoundException(): void
    {
        $port = new Port('left');
        $tree = new ExpressionTree($port, ['left' => $port]);

        $this->expectException(PortNotBoundException::class);

        $tree();
    }

    /**
     * Ports lets the same name => Port map used while building the root also be handed straight
     * to ExpressionTree, instead of separately declaring each Port, using it in the facade chain,
     * and re-listing it a third time in a hand-written array.
     */
    public function testCanBeConstructedDirectlyFromAPortsInstance(): void
    {
        $ports = new Ports();
        $root = Arithmetic::of(new FloatCast($ports->named('base')))
            ->multiply(new FloatCast($ports->named('rate')))
            ->build();

        $tree = new ExpressionTree($root, $ports);

        self::assertSame(['base', 'rate'], $tree->inputNames());

        $tree->bind('base', new FloatLiteral(1000.0))->bind('rate', new FloatLiteral(0.05));

        self::assertSame(50.0, $tree());
    }
}
