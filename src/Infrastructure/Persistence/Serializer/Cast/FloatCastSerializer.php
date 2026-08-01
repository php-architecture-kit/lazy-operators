<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Cast;

use PhpArchitecture\LazyOperators\Foundation\Cast\FloatCast;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class FloatCastSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof FloatCast;
    }

    public function expressionVersion(): string
    {
        return FloatCast::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof FloatCast);

        return [
            'uid' => FloatCast::UID,
            'key' => FloatCast::KEY,
            'class' => FloatCast::class,
            'version' => FloatCast::VERSION,
            'args' => [
                'value' => $registry->serialize($expression->value),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new FloatCast($registry->deserialize($data['args']['value']));
    }
}
