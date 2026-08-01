<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Cast;

use PhpArchitecture\LazyOperators\Foundation\Cast\IntegerCast;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class IntegerCastSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof IntegerCast;
    }

    public function expressionVersion(): string
    {
        return IntegerCast::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof IntegerCast);

        return [
            'uid' => IntegerCast::UID,
            'key' => IntegerCast::KEY,
            'class' => IntegerCast::class,
            'version' => IntegerCast::VERSION,
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
        return new IntegerCast($registry->deserialize($data['args']['value']));
    }
}
