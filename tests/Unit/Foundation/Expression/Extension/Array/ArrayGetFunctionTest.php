<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Array;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Array\ArrayGetFunction;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\ArrayLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PHPUnit\Framework\TestCase;

final class ArrayGetFunctionTest extends TestCase
{
    public function testReturnsTheValueAtATopLevelPath(): void
    {
        $function = new ArrayGetFunction(
            new ArrayLiteral(['name' => 'Ada']),
            new StringLiteral('name'),
        );

        self::assertSame('Ada', $function());
    }

    public function testReturnsTheValueAtANestedPath(): void
    {
        $function = new ArrayGetFunction(
            new ArrayLiteral(['user' => ['address' => ['city' => 'London']]]),
            new StringLiteral('user.address.city'),
        );

        self::assertSame('London', $function());
    }

    public function testReturnsNullWhenASegmentIsMissing(): void
    {
        $function = new ArrayGetFunction(
            new ArrayLiteral(['user' => ['address' => ['city' => 'London']]]),
            new StringLiteral('user.address.country'),
        );

        self::assertNull($function());
    }

    public function testReturnsNullWhenAnIntermediateSegmentIsNotAnArray(): void
    {
        $function = new ArrayGetFunction(
            new ArrayLiteral(['user' => ['name' => 'Ada']]),
            new StringLiteral('user.name.first'),
        );

        self::assertNull($function());
    }
}
