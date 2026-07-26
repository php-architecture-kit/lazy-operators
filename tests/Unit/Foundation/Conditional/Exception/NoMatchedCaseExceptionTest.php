<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Conditional\Exception;

use PhpArchitecture\LazyOperators\Foundation\Conditional\Exception\NoMatchedCaseException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class NoMatchedCaseExceptionTest extends TestCase
{
    public function testExtendsRuntimeException(): void
    {
        self::assertInstanceOf(RuntimeException::class, new NoMatchedCaseException());
    }

    public function testHasExpectedMessage(): void
    {
        self::assertSame('No matched case', (new NoMatchedCaseException())->getMessage());
    }
}
