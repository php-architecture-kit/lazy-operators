<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Arithmetic\ModuloOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class ModuloOperatorSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof ModuloOperator;
    }

    public function expressionVersion(): string
    {
        return ModuloOperator::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof ModuloOperator);

        return [
            'uid' => ModuloOperator::UID,
            'key' => ModuloOperator::KEY,
            'class' => ModuloOperator::class,
            'version' => ModuloOperator::VERSION,
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
        return new ModuloOperator(
            $registry->deserialize($data['args'][0]),
            $registry->deserialize($data['args'][1]),
        );
    }
}
