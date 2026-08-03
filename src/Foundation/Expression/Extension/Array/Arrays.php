<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Extension\Array;

use PhpArchitecture\LazyOperators\Foundation\Expression\Type\ArrayValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Type\StringValue;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use PhpArchitecture\LazyOperators\Foundation\Expression\PipelineConfig;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\DecoratesNodes;
use PhpArchitecture\LazyOperators\Foundation\Expression\Support\WrapsRawValues;

/**
 * Static factories for genuinely array-shaped operations — array in, single value out.
 * Unbounded-input-to-single-scalar reductions (sum, product) live in Extension\List instead.
 */
class Arrays
{
    use WrapsRawValues;
    use DecoratesNodes;

    /**
     * @param array<array-key,mixed>|Expression $array
     */
    public static function get(array|Expression $array, string|Expression $path, ?PipelineConfig $config = null): Expression
    {
        $config ??= new PipelineConfig();

        return self::decorate(
            new ArrayGetFunction(
                self::wrapAs(ArrayValue::class, $array),
                self::wrapAs(StringValue::class, $path),
            ),
            $config,
        );
    }

}
