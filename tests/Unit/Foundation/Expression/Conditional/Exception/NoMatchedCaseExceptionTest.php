<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Conditional\Exception;

use PhpArchitecture\LazyOperators\Foundation\Conditional\Exception\LazyOperatorsConditionalException;
use PhpArchitecture\LazyOperators\Foundation\Conditional\Exception\NoMatchedCaseException;
use PhpArchitecture\LazyOperators\Foundation\Exception\LazyOperatorsException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

final class NoMatchedCaseExceptionTest extends TestCase
{
    public function testCreateBuildsAMessageDescribingTheUnmatchedValue(): void
    {
        self::assertSame(
            "No case matched value of type int (2).",
            NoMatchedCaseException::create(2)->getMessage(),
        );
    }

    public function testExtendsRuntimeException(): void
    {
        self::assertInstanceOf(RuntimeException::class, NoMatchedCaseException::create(null));
    }

    public function testImplementsLazyOperatorsConditionalException(): void
    {
        self::assertInstanceOf(LazyOperatorsConditionalException::class, NoMatchedCaseException::create(null));
    }

    public function testImplementsLazyOperatorsException(): void
    {
        self::assertInstanceOf(LazyOperatorsException::class, NoMatchedCaseException::create(null));
    }
}
