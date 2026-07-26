<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Comparison\EqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class EqualOperatorSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof EqualOperator;
    }

    public function expressionVersion(): string
    {
        return EqualOperator::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof EqualOperator);

        return [
            'uid' => EqualOperator::UID,
            'key' => EqualOperator::KEY,
            'class' => EqualOperator::class,
            'version' => EqualOperator::VERSION,
            'args' => [
                $registry->serialize($expression->left),
                $registry->serialize($expression->right),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new EqualOperator(
            $registry->deserialize($data['args'][0]),
            $registry->deserialize($data['args'][1]),
        );
    }
}
