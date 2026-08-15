<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression;

use PhpArchitecture\LazyOperators\Foundation\Expression\Port;
use PhpArchitecture\LazyOperators\Foundation\Expression\Ports;
use PHPUnit\Framework\TestCase;

final class PortsTest extends TestCase
{
    public function testNamedCreatesAPortWithTheGivenName(): void
    {
        $ports = new Ports();

        $port = $ports->named('base');

        self::assertInstanceOf(Port::class, $port);
        self::assertSame('base', $port->name);
    }

    public function testNamedReturnsTheSamePortInstanceForTheSameNameOnRepeatedCalls(): void
    {
        $ports = new Ports();

        self::assertSame($ports->named('base'), $ports->named('base'));
    }

    public function testToArrayReflectsEveryDistinctNameRequestedSoFar(): void
    {
        $ports = new Ports();
        $base = $ports->named('base');
        $rate = $ports->named('rate');

        self::assertSame(['base' => $base, 'rate' => $rate], $ports->toArray());
    }

    public function testToArrayIsEmptyWhenNoPortWasEverNamed(): void
    {
        self::assertSame([], (new Ports())->toArray());
    }
}
