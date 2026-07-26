<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Comparison;

use PhpArchitecture\LazyOperators\Foundation\Comparison\LessThanOrEqualOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class LessThanOrEqualOperatorSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof LessThanOrEqualOperator;
    }

    public function expressionVersion(): string
    {
        return LessThanOrEqualOperator::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof LessThanOrEqualOperator);

        return [
            'uid' => LessThanOrEqualOperator::UID,
            'key' => LessThanOrEqualOperator::KEY,
            'class' => LessThanOrEqualOperator::class,
            'version' => LessThanOrEqualOperator::VERSION,
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
        return new LessThanOrEqualOperator(
            $registry->deserialize($data['args'][0]),
            $registry->deserialize($data['args'][1]),
        );
    }
}
