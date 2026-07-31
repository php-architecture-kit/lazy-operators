<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\MaxFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;

class MaxFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof MaxFunction;
    }

    public function expressionVersion(): string
    {
        return MaxFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof MaxFunction);

        return [
            'uid' => MaxFunction::UID,
            'key' => MaxFunction::KEY,
            'class' => MaxFunction::class,
            'version' => MaxFunction::VERSION,
            'args' => array_map(
                static fn (Expression $value) => $registry->serialize($value),
                $expression->values,
            ),
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        $values = array_map(
            fn (mixed $value) => $this->deserializeAs($registry, $value, NumberValue::class),
            $data['args'],
        );

        return new MaxFunction(...$values);
    }
}
