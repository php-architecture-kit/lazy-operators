<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Cast;

use PhpArchitecture\LazyOperators\Foundation\Cast\StringCast;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class StringCastSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof StringCast;
    }

    public function expressionVersion(): string
    {
        return StringCast::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof StringCast);

        return [
            'uid' => StringCast::UID,
            'key' => StringCast::KEY,
            'class' => StringCast::class,
            'version' => StringCast::VERSION,
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
        return new StringCast($registry->deserialize($data['args']['value']));
    }
}
