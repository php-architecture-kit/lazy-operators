<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Static;

use PhpArchitecture\LazyOperators\Foundation\Static\Value;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ValueTest extends TestCase
{
    public function testWrapsAnInteger(): void
    {
        $value = new Value(42);

        self::assertSame(42, $value());
    }

    public function testWrapsAString(): void
    {
        $value = new Value('hello');

        self::assertSame('hello', $value());
    }

    public function testWrapsAnArray(): void
    {
        $array = ['a', 'b'];
        $value = new Value($array);

        self::assertSame($array, $value());
    }

    public function testWrapsNull(): void
    {
        $value = new Value(null);

        self::assertNull($value());
    }

    public function testWrapsAnObjectByIdentity(): void
    {
        $object = new stdClass();
        $value = new Value($object);

        self::assertSame($object, $value());
    }
}
