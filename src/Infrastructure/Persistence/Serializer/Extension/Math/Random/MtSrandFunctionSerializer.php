<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Random;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Random\MtSrandFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;
class MtSrandFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof MtSrandFunction;
    }

    public function expressionVersion(): string
    {
        return MtSrandFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof MtSrandFunction);

        return [
            'uid' => MtSrandFunction::UID,
            'key' => MtSrandFunction::KEY,
            'class' => MtSrandFunction::class,
            'version' => MtSrandFunction::VERSION,
            'args' => [
                'seed' => $registry->serialize($expression->seed),
                'mode' => $expression->mode,
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new MtSrandFunction(
            $this->deserializeAs($registry, $data['args']['seed'], NumberValue::class),
            $data['args']['mode'],
        );
    }
}
