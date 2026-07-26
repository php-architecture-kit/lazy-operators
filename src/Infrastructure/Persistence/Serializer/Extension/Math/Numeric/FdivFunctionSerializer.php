<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\FdivFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class FdivFunctionSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof FdivFunction;
    }

    public function expressionVersion(): string
    {
        return FdivFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof FdivFunction);

        return [
            'uid' => FdivFunction::UID,
            'key' => FdivFunction::KEY,
            'class' => FdivFunction::class,
            'version' => FdivFunction::VERSION,
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
        return new FdivFunction(
            $registry->deserialize($data['args']['dividend']),
            $registry->deserialize($data['args']['divisor']),
        );
    }
}
