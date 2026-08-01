<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Runtime;

use PhpArchitecture\LazyOperators\Foundation\Exception\PortNotBoundException;
use PhpArchitecture\LazyOperators\Foundation\Runtime\Port;
use PhpArchitecture\LazyOperators\Foundation\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Static\StringLiteral;
use PHPUnit\Framework\TestCase;

final class PortTest extends TestCase
{
    public function testInvokingAnUnboundPortThrows(): void
    {
        $port = new Port();

        $this->expectException(PortNotBoundException::class);

        $port();
    }

    public function testInvokingAfterSetExprDelegatesToTheBoundExpression(): void
    {
        $port = new Port();
        $port->setExpr(new IntLiteral(42));

        self::assertSame(42, $port());
    }

    public function testSetExprCanRebindToADifferentExpression(): void
    {
        $port = new Port();
        $port->setExpr(new IntLiteral(1));
        $port->setExpr(new StringLiteral('rebound'));

        self::assertSame('rebound', $port());
    }
}
