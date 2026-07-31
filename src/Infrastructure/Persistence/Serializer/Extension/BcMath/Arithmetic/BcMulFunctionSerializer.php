<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcMulFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;

class BcMulFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof BcMulFunction;
    }

    public function expressionVersion(): string
    {
        return BcMulFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof BcMulFunction);

        return [
            'uid' => BcMulFunction::UID,
            'key' => BcMulFunction::KEY,
            'class' => BcMulFunction::class,
            'version' => BcMulFunction::VERSION,
            'args' => [
                $registry->serialize($expression->left),
                $registry->serialize($expression->right),
                $expression->scale,
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new BcMulFunction(
            $this->deserializeAs($registry, $data['args'][0], PrecisionNumberValue::class),
            $this->deserializeAs($registry, $data['args'][1], PrecisionNumberValue::class),
            $data['args'][2],
        );
    }
}
