<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\IntdivFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class IntdivFunctionSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof IntdivFunction;
    }

    public function expressionVersion(): string
    {
        return IntdivFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof IntdivFunction);

        return [
            'uid' => IntdivFunction::UID,
            'key' => IntdivFunction::KEY,
            'class' => IntdivFunction::class,
            'version' => IntdivFunction::VERSION,
            'args' => [
                'dividend' => $registry->serialize($expression->dividend),
                'divisor' => $registry->serialize($expression->divisor),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new IntdivFunction(
            $registry->deserialize($data['args']['dividend']),
            $registry->deserialize($data['args']['divisor']),
        );
    }
}
