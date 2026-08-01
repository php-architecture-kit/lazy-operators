<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\BcMath\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\NumberValueToPrecisionAdapter;
use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;

class NumberValueToPrecisionAdapterSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof NumberValueToPrecisionAdapter;
    }

    public function expressionVersion(): string
    {
        return NumberValueToPrecisionAdapter::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof NumberValueToPrecisionAdapter);

        return [
            'uid' => NumberValueToPrecisionAdapter::UID,
            'key' => NumberValueToPrecisionAdapter::KEY,
            'class' => NumberValueToPrecisionAdapter::class,
            'version' => NumberValueToPrecisionAdapter::VERSION,
            'args' => [$registry->serialize($expression->value)],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new NumberValueToPrecisionAdapter($this->deserializeAs($registry, $data['args'][0], NumberValue::class));
    }
}
