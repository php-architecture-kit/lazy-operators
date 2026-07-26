<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Custom;

use PhpArchitecture\LazyOperators\Foundation\Custom\CallbackOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class CallbackOperatorSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof CallbackOperator;
    }

    public function expressionVersion(): string
    {
        return CallbackOperator::VERSION;
    }

    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        return [
            'uid' => CallbackOperator::UID,
            'key' => CallbackOperator::KEY,
            'class' => CallbackOperator::class,
            'version' => CallbackOperator::VERSION,
            'args' => [
                'callback' => $registry->callbacks()->nameFor($expression->callback),
                'arguments' => array_map(
                    static fn (Expression $argument) => $registry->serialize($argument),
                    $expression->arguments,
                ),
            ],
        ];
    }

    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new CallbackOperator(
            $registry->callbacks()->resolve($data['args']['callback']),
            ...array_map(
                static fn (array $argument) => $registry->deserialize($argument),
                $data['args']['arguments'],
            ),
        );
    }
}
