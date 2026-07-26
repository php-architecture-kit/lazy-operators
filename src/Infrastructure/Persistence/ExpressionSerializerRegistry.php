<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence;

use PhpArchitecture\LazyOperators\Foundation\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\IncompatibleExpressionVersionException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnknownExpressionUidException;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Exception\UnsupportedExpressionException;

final class ExpressionSerializerRegistry
{
    /**
     * @param array<string, ExpressionSerializer> $serializers keyed by Expression UID
     */
    public function __construct(
        private readonly array $serializers,
        private readonly CallbackRegistry $callbacks = new CallbackRegistry(),
    ) {
    }

    public function callbacks(): CallbackRegistry
    {
        return $this->callbacks;
    }

    public function serialize(Expression $expression): array
    {
        while ($expression instanceof Decorator) {
            $expression = $expression->unwrap();
        }

        foreach ($this->serializers as $serializer) {
            if ($serializer->supports($expression)) {
                return $serializer->serialize($expression, $this);
            }
        }

        throw new UnsupportedExpressionException($expression::class);
    }

    public function deserialize(array $data): Expression
    {
        $serializer = $this->serializers[$data['uid']] ?? throw new UnknownExpressionUidException($data['uid']);

        if ($data['version'] !== $serializer->expressionVersion()) {
            throw new IncompatibleExpressionVersionException($data['uid'], $data['version'], $serializer->expressionVersion());
        }

        return $serializer->deserialize($data, $this);
    }
}
