<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtRandFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class MtRandFunctionSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof MtRandFunction;
    }

    public function expressionVersion(): string
    {
        return MtRandFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof MtRandFunction);

        return [
            'uid' => MtRandFunction::UID,
            'key' => MtRandFunction::KEY,
            'class' => MtRandFunction::class,
            'version' => MtRandFunction::VERSION,
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
        return new MtRandFunction(
            $data['args']['min'] !== null ? $registry->deserialize($data['args']['min']) : null,
            $data['args']['max'] !== null ? $registry->deserialize($data['args']['max']) : null,
        );
    }
}
