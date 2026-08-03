<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Allocation;

use PhpArchitecture\LazyOperators\Foundation\Extension\Allocation\Exception\EmptySharesException;
use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

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
        ?PipelineConfig $config = null,
    ): Expression {
        if ($shares === []) {
            throw EmptySharesException::create();
        }

        $config ??= new PipelineConfig();

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

    private static function decorateNumeric(int|float|NumberValue $value, PipelineConfig $config): NumberValue
    {
        return self::decorateNumber(self::wrapAs(NumberValue::class, $value), $config);
    }
}
