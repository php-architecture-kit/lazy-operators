<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Numeric;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Numeric\FmodFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;
class FmodFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof FmodFunction;
    }

    public function expressionVersion(): string
    {
        return FmodFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof FmodFunction);

        return [
            'uid' => FmodFunction::UID,
            'key' => FmodFunction::KEY,
            'class' => FmodFunction::class,
            'version' => FmodFunction::VERSION,
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
        return new FmodFunction(
            $this->deserializeAs($registry, $data['args']['dividend'], NumberValue::class),
            $this->deserializeAs($registry, $data['args']['divisor'], NumberValue::class),
        );
    }
}
