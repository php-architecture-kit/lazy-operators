<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\MultiplicationOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class MultiplicationOperatorSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof MultiplicationOperator;
    }

    public function expressionVersion(): string
    {
        return MultiplicationOperator::VERSION;
    }

    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        return [
            'uid' => MultiplicationOperator::UID,
            'key' => MultiplicationOperator::KEY,
            'class' => MultiplicationOperator::class,
            'version' => MultiplicationOperator::VERSION,
            'args' => [
                $registry->serialize($expression->left),
                $registry->serialize($expression->right),
            ],
        ];
    }

    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new MultiplicationOperator(
            $registry->deserialize($data['args'][0]),
            $registry->deserialize($data['args'][1]),
        );
    }
}
