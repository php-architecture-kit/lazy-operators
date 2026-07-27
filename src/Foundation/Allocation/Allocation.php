<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Allocation;

use PhpArchitecture\LazyOperators\Foundation\Allocation\Exception\EmptySharesException;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Support\WrapsRawValues;

class Allocation
{
    use WrapsRawValues;
    use DecoratesNodes;

    /**
     * @param non-empty-array<int|float|Expression> $shares
     */
    public static function allocate(
        int|float|Expression $amount,
        array $shares,
        int|Expression $precision,
        AllocationRemainderTarget $remainderTarget = AllocationRemainderTarget::First,
        ?PipelineConfig $config = null,
    ): Expression {
        if ($shares === []) {
            throw EmptySharesException::create();
        }

        $config ??= new PipelineConfig();

        $decorated = array_map(
            static fn (int|float|Expression $share) => self::decorate(self::wrap($share), $config),
            array_values($shares),
        );
        $first = array_shift($decorated);
        $rest = $decorated;

        return self::decorate(
            new AllocationFunction(
                self::decorate(self::wrap($amount), $config),
                self::decorate(self::wrap($precision), $config),
                $remainderTarget,
                $first,
                ...$rest,
            ),
            $config,
        );
    }
}
