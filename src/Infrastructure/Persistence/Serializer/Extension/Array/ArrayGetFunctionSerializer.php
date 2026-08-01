<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Array;

use PhpArchitecture\LazyOperators\Foundation\Type\ArrayValue;
use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Array\ArrayGetFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;

class ArrayGetFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof ArrayGetFunction;
    }

    public function expressionVersion(): string
    {
        return ArrayGetFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof ArrayGetFunction);

        return [
            'uid' => ArrayGetFunction::UID,
            'key' => ArrayGetFunction::KEY,
            'class' => ArrayGetFunction::class,
            'version' => ArrayGetFunction::VERSION,
            'args' => [
                $registry->serialize($expression->array),
                $registry->serialize($expression->path),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new ArrayGetFunction(
            $this->deserializeAs($registry, $data['args'][0], ArrayValue::class),
            $this->deserializeAs($registry, $data['args'][1], StringValue::class),
        );
    }
}
