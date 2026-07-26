<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\RandomIntFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class RandomIntFunctionSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof RandomIntFunction;
    }

    public function expressionVersion(): string
    {
        return RandomIntFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof RandomIntFunction);

        return [
            'uid' => RandomIntFunction::UID,
            'key' => RandomIntFunction::KEY,
            'class' => RandomIntFunction::class,
            'version' => RandomIntFunction::VERSION,
            'args' => [
                'min' => $registry->serialize($expression->min),
                'max' => $registry->serialize($expression->max),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new RandomIntFunction(
            $registry->deserialize($data['args']['min']),
            $registry->deserialize($data['args']['max']),
        );
    }
}
