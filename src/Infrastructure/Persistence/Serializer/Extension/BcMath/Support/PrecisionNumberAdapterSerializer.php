<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\BcMath\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Support\PrecisionNumberAdapter;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class PrecisionNumberAdapterSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof PrecisionNumberAdapter;
    }

    public function expressionVersion(): string
    {
        return PrecisionNumberAdapter::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof PrecisionNumberAdapter);

        return [
            'uid' => PrecisionNumberAdapter::UID,
            'key' => PrecisionNumberAdapter::KEY,
            'class' => PrecisionNumberAdapter::class,
            'version' => PrecisionNumberAdapter::VERSION,
            'args' => [$registry->serialize($expression->value)],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new PrecisionNumberAdapter($registry->deserialize($data['args'][0]));
    }
}
