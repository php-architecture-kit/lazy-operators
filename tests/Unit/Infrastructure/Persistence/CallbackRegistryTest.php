<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Infrastructure\Persistence;

use PhpArchitecture\LazyOperators\Infrastructure\Persistence\CallbackRegistry;
use PHPUnit\Framework\TestCase;

final class CallbackRegistryTest extends TestCase
{
    public function testNamesReturnsEmptyArrayWhenNothingIsRegistered(): void
    {
        self::assertSame([], (new CallbackRegistry())->names());
    }

    public function testNamesReturnsEveryRegisteredNameInRegistrationOrder(): void
    {
        $registry = new CallbackRegistry();
        $registry->register('sum', static fn (int $a, int $b): int => $a + $b);
        $registry->register('double', static fn (int $a): int => $a * 2);

        self::assertSame(['sum', 'double'], $registry->names());
    }
}
