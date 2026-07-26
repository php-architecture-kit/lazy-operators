<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Exponential;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Exponential\PowFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class PowFunctionSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof PowFunction;
    }

    public function expressionVersion(): string
    {
        return PowFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof PowFunction);

        return [
            'uid' => PowFunction::UID,
            'key' => PowFunction::KEY,
            'class' => PowFunction::class,
            'version' => PowFunction::VERSION,
            'args' => [
                'base' => $registry->serialize($expression->base),
                'exponent' => $registry->serialize($expression->exponent),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new PowFunction(
            $registry->deserialize($data['args']['base']),
            $registry->deserialize($data['args']['exponent']),
        );
    }
}
