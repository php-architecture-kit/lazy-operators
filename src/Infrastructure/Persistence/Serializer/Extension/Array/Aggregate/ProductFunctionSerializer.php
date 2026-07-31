<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Array\Aggregate;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Array\Aggregate\ProductFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;

class ProductFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof ProductFunction;
    }

    public function expressionVersion(): string
    {
        return ProductFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof ProductFunction);

        return [
            'uid' => ProductFunction::UID,
            'key' => ProductFunction::KEY,
            'class' => ProductFunction::class,
            'version' => ProductFunction::VERSION,
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

        return new ProductFunction(...$values);
    }
}
