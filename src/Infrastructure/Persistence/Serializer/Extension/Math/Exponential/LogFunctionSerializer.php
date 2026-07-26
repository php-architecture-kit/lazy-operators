<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\LogFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class LogFunctionSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof LogFunction;
    }

    public function expressionVersion(): string
    {
        return LogFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof LogFunction);

        return [
            'uid' => LogFunction::UID,
            'key' => LogFunction::KEY,
            'class' => LogFunction::class,
            'version' => LogFunction::VERSION,
            'args' => [
                'value' => $registry->serialize($expression->value),
                'base' => $registry->serialize($expression->base),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new LogFunction(
            $registry->deserialize($data['args']['value']),
            $registry->deserialize($data['args']['base']),
        );
    }
}
