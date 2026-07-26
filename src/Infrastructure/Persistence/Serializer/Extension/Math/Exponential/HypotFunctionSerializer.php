<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\HypotFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class HypotFunctionSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof HypotFunction;
    }

    public function expressionVersion(): string
    {
        return HypotFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof HypotFunction);

        return [
            'uid' => HypotFunction::UID,
            'key' => HypotFunction::KEY,
            'class' => HypotFunction::class,
            'version' => HypotFunction::VERSION,
            'args' => [
                'x' => $registry->serialize($expression->x),
                'y' => $registry->serialize($expression->y),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new HypotFunction(
            $registry->deserialize($data['args']['x']),
            $registry->deserialize($data['args']['y']),
        );
    }
}
