<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class RandFunctionSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof RandFunction;
    }

    public function expressionVersion(): string
    {
        return RandFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof RandFunction);

        return [
            'uid' => RandFunction::UID,
            'key' => RandFunction::KEY,
            'class' => RandFunction::class,
            'version' => RandFunction::VERSION,
            'args' => [
                'min' => $expression->min !== null ? $registry->serialize($expression->min) : null,
                'max' => $expression->max !== null ? $registry->serialize($expression->max) : null,
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new RandFunction(
            $data['args']['min'] !== null ? $registry->deserialize($data['args']['min']) : null,
            $data['args']['max'] !== null ? $registry->deserialize($data['args']['max']) : null,
        );
    }
}
