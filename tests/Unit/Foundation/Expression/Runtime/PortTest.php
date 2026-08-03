<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Runtime;

use PhpArchitecture\LazyOperators\Foundation\Expression\Exception\PortNotBoundException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Runtime\Port;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PHPUnit\Framework\TestCase;

final class PortTest extends TestCase
{
    public function testInvokingAnUnboundPortThrows(): void
    {
        $port = new Port('amount');

        $this->expectException(PortNotBoundException::class);

        $port();
    }

    public function testInvokingAfterSetExprDelegatesToTheBoundExpression(): void
    {
        $port = new Port('amount');
        $port->setExpr(new IntLiteral(42));

        self::assertSame(42, $port());
    }

    public function testSetExprCanRebindToADifferentExpression(): void
    {
        $port = new Port('amount');
        $port->setExpr(new IntLiteral(1));
        $port->setExpr(new StringLiteral('rebound'));

        self::assertSame('rebound', $port());
    }

    public function testNameIsExposed(): void
    {
        $port = new Port('amount');

        self::assertSame('amount', $port->name);
    }
}
