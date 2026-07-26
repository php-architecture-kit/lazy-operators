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

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
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

        throw UnsupportedExpressionException::create($expression::class);
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data): Expression
    {
        $serializer = $this->serializers[$data['uid']] ?? throw UnknownExpressionUidException::create($data['uid']);

        if ($data['version'] !== $serializer->expressionVersion()) {
            throw IncompatibleExpressionVersionException::create($data['uid'], $data['version'], $serializer->expressionVersion());
        }

        return $serializer->deserialize($data, $this);
    }
}
