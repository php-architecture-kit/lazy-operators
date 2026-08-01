<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Cast;

use PhpArchitecture\LazyOperators\Foundation\Cast\BooleanCast;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class BooleanCastSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof BooleanCast;
    }

    public function expressionVersion(): string
    {
        return BooleanCast::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof BooleanCast);

        return [
            'uid' => BooleanCast::UID,
            'key' => BooleanCast::KEY,
            'class' => BooleanCast::class,
            'version' => BooleanCast::VERSION,
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
        return new BooleanCast($registry->deserialize($data['args']['value']));
    }
}
