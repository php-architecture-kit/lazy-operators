<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\BcMath\Arithmetic;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\Arithmetic\BcSubFunction;
use PhpArchitecture\LazyOperators\Foundation\Extension\BcMath\PrecisionNumberValue;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;

class BcSubFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof BcSubFunction;
    }

    public function expressionVersion(): string
    {
        return BcSubFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof BcSubFunction);

        return [
            'uid' => BcSubFunction::UID,
            'key' => BcSubFunction::KEY,
            'class' => BcSubFunction::class,
            'version' => BcSubFunction::VERSION,
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
        return new BcSubFunction(
            $this->deserializeAs($registry, $data['args'][0], PrecisionNumberValue::class),
            $this->deserializeAs($registry, $data['args'][1], PrecisionNumberValue::class),
            $data['args'][2],
        );
    }
}
