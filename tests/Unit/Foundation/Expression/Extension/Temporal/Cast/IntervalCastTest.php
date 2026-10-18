<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Unit\Foundation\Expression\Extension\Temporal\Cast;

use DateInterval;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Cast\IntervalCast;
use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Temporal\Exception\InvalidDateTimeException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\IntLiteral;
use PhpArchitecture\LazyOperators\Foundation\Expression\Static\StringLiteral;
use PhpArchitecture\LazyOperators\Tests\Support\SpyExpression;
use PHPUnit\Framework\TestCase;

final class IntervalCastTest extends TestCase
{
    public function testAnIsoStringIsRead(): void
    {
        self::assertSame(7, (new IntervalCast(new StringLiteral('P7D')))()->d);
    }

    public function testADateIntervalIsCopiedSoTheSourceCannotBeChanged(): void
    {
        $source = new DateInterval('PT30M');
        $cast = new IntervalCast(new SpyExpression($source));

        $result = $cast();
        $result->i = 45;

        self::assertNotSame($source, $result);
        self::assertSame(30, $source->i);
    }

    public function testAMalformedStringIsRejected(): void
    {
        $this->expectException(InvalidDateTimeException::class);

        (new IntervalCast(new StringLiteral('7 days')))();
    }

    public function testAnUnsupportedValueIsRejected(): void
    {
        $this->expectException(InvalidDateTimeException::class);

        (new IntervalCast(new IntLiteral(7)))();
    }
}
