<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Allocation;

use PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Allocation\Exception\EmptySharesException;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\ExpressionTreeConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\WrapsRawValues;

class Allocation
{
    use WrapsRawValues;
    use DecoratesNodes;

    /**
     * @param non-empty-array<int|float|NumberValue> $shares
     */
    public static function allocate(
        int|float|NumberValue $amount,
        array $shares,
        int|IntegerValue $precision,
        AllocationRemainderTarget $remainderTarget = AllocationRemainderTarget::First,
        ?ExpressionTreeConfig $config = null,
    ): Expression {
        if ($shares === []) {
            throw EmptySharesException::create();
        }

        $config ??= new ExpressionTreeConfig();

        $decorated = array_map(
            static fn (int|float|NumberValue $share) => self::decorateNumeric($share, $config),
            array_values($shares),
        );
        $first = array_shift($decorated);
        $rest = $decorated;

        return self::decorate(
            new AllocationFunction(
                self::decorateNumeric($amount, $config),
                self::decorateInteger(self::wrapAs(IntegerValue::class, $precision), $config),
                $remainderTarget,
                $first,
                ...$rest,
            ),
            $config,
        );
    }

    private static function decorateNumeric(int|float|NumberValue $value, ExpressionTreeConfig $config): NumberValue
    {
        return self::decorateNumber(self::wrapAs(NumberValue::class, $value), $config);
    }
}
