<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Logical;

use PhpArchitecture\LazyOperators\Foundation\Logical\XorOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class XorOperatorSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof XorOperator;
    }

    public function expressionVersion(): string
    {
        return XorOperator::VERSION;
    }

    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        return [
            'uid' => XorOperator::UID,
            'key' => XorOperator::KEY,
            'class' => XorOperator::class,
            'version' => XorOperator::VERSION,
            'args' => [
                $registry->serialize($expression->left),
                $registry->serialize($expression->right),
            ],
        ];
    }

    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new XorOperator(
            $registry->deserialize($data['args'][0]),
            $registry->deserialize($data['args'][1]),
        );
    }
}
