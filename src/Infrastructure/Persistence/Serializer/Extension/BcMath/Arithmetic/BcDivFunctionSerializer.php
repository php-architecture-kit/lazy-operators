<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcDivFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;
use PhpArchitecture\LazyOperators\Foundation\Type\IntegerValue;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;

class BcDivFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof BcDivFunction;
    }

    public function expressionVersion(): string
    {
        return BcDivFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof BcDivFunction);

        return [
            'uid' => BcDivFunction::UID,
            'key' => BcDivFunction::KEY,
            'class' => BcDivFunction::class,
            'version' => BcDivFunction::VERSION,
            'args' => [
                $registry->serialize($expression->dividend),
                $registry->serialize($expression->divisor),
                $expression->scale === null ? null : $registry->serialize($expression->scale),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new BcDivFunction(
            $this->deserializeAs($registry, $data['args'][0], PrecisionNumberValue::class),
            $this->deserializeAs($registry, $data['args'][1], PrecisionNumberValue::class),
            $data['args'][2] === null ? null : $this->deserializeAs($registry, $data['args'][2], IntegerValue::class),
        );
    }
}
