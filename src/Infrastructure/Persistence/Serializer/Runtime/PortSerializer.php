<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Runtime;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Runtime\Port;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

/**
 * A Port's currently-bound Expression is deliberately not persisted: it is meant to be rebound at
 * runtime for each execution, not restored from disk. Deserializing always yields a fresh, unbound
 * Port.
 */
class PortSerializer implements ExpressionSerializer
{
    public function supports(Expression $expression): bool
    {
        return $expression instanceof Port;
    }

    public function expressionVersion(): string
    {
        return Port::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof Port);

        return [
            'uid' => Port::UID,
            'key' => Port::KEY,
            'class' => Port::class,
            'version' => Port::VERSION,
            'args' => [],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new Port();
    }
}
