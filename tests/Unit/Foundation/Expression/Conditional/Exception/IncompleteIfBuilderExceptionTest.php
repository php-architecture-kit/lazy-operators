<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Conditional\Exception;

use LogicException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\IncompleteIfBuilderException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception\LazyOperatorsConditionalException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Exception\LazyOperatorsException;
use PHPUnit\Framework\TestCase;

final class IncompleteIfBuilderExceptionTest extends TestCase
{
    public function testCreateNamesBothWhenBothAreMissing(): void
    {
        self::assertSame(
            'IfBuilder requires then() and else() to be set before build().',
            IncompleteIfBuilderException::create(missingThen: true, missingElse: true)->getMessage(),
        );
    }

    public function testCreateNamesOnlyThenWhenElseIsPresent(): void
    {
        self::assertSame(
            'IfBuilder requires then() to be set before build().',
            IncompleteIfBuilderException::create(missingThen: true, missingElse: false)->getMessage(),
        );
    }

    public function testCreateNamesOnlyElseWhenThenIsPresent(): void
    {
        self::assertSame(
            'IfBuilder requires else() to be set before build().',
            IncompleteIfBuilderException::create(missingThen: false, missingElse: true)->getMessage(),
        );
    }

    public function testExtendsLogicException(): void
    {
        self::assertInstanceOf(LogicException::class, IncompleteIfBuilderException::create(true, true));
    }

    public function testImplementsLazyOperatorsConditionalException(): void
    {
        self::assertInstanceOf(LazyOperatorsConditionalException::class, IncompleteIfBuilderException::create(true, true));
    }

    public function testImplementsLazyOperatorsException(): void
    {
        self::assertInstanceOf(LazyOperatorsException::class, IncompleteIfBuilderException::create(true, true));
    }
}
