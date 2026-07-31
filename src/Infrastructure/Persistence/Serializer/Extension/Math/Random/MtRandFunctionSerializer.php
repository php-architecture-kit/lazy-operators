<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtRandFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;
class MtRandFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

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
            $data['args']['min'] !== null ? $this->deserializeAs($registry, $data['args']['min'], NumberValue::class) : null,
            $data['args']['max'] !== null ? $this->deserializeAs($registry, $data['args']['max'], NumberValue::class) : null,
        );
    }
}
