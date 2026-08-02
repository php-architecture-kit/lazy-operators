<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Infrastructure\Registry;

use PhpArchitecture\LazyOperators\Infrastructure\Registry\CallbackRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnpersistableCallbackException;
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

    public function testGetCallbackDetailsReflectsParameterNamesTypesAndReturnType(): void
    {
        $registry = new CallbackRegistry();
        $registry->register('round2', static fn (float $value): float => round($value, 2));

        $details = $registry->getCallbackDetails('round2');

        self::assertSame('round2', $details->name);
        self::assertSame('float', $details->returnType);
        self::assertCount(1, $details->parameters);
        self::assertSame('value', $details->parameters[0]->name);
        self::assertSame('float', $details->parameters[0]->type);
        self::assertSame('(float $value): float', $details->signature);
    }

    public function testGetCallbackDetailsFormatsMultipleParametersAndNoReturnType(): void
    {
        $registry = new CallbackRegistry();
        $registry->register('sum', static function (int $a, int $b) {
            return $a + $b;
        });

        $details = $registry->getCallbackDetails('sum');

        self::assertNull($details->returnType);
        self::assertSame(['a', 'b'], array_map(static fn ($parameter) => $parameter->name, $details->parameters));
        self::assertSame('(int $a, int $b)', $details->signature);
    }

    public function testGetCallbackDetailsThrowsForAnUnregisteredName(): void
    {
        $registry = new CallbackRegistry();

        $this->expectException(UnpersistableCallbackException::class);

        $registry->getCallbackDetails('missing');
    }
}
