<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\List\Aggregate;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\List\Aggregate\SumFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;

class SumFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof SumFunction;
    }

    public function expressionVersion(): string
    {
        return SumFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof SumFunction);

        return [
            'uid' => SumFunction::UID,
            'key' => SumFunction::KEY,
            'class' => SumFunction::class,
            'version' => SumFunction::VERSION,
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

        return new SumFunction(...$values);
    }
}
