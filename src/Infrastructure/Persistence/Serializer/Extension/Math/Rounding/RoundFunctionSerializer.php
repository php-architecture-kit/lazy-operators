<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Extension\Math\Rounding;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Extension\Math\Rounding\RoundFunction;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;
use RoundingMode;

use PhpArchitecture\LazyOperators\Foundation\Type\NumberValue;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Support\DeserializesNarrowly;
class RoundFunctionSerializer implements ExpressionSerializer
{
    use DeserializesNarrowly;

    public function supports(Expression $expression): bool
    {
        return $expression instanceof RoundFunction;
    }

    public function expressionVersion(): string
    {
        return RoundFunction::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof RoundFunction);

        return [
            'uid' => RoundFunction::UID,
            'key' => RoundFunction::KEY,
            'class' => RoundFunction::class,
            'version' => RoundFunction::VERSION,
            'args' => [
                'value' => $registry->serialize($expression->value),
                'precision' => $registry->serialize($expression->precision),
                'mode' => $expression->mode instanceof RoundingMode
                    ? ['type' => 'enum', 'case' => $expression->mode->name]
                    : ['type' => 'int', 'value' => $expression->mode],
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        $mode = $data['args']['mode'];

        return new RoundFunction(
            $this->deserializeAs($registry, $data['args']['value'], NumberValue::class),
            $this->deserializeAs($registry, $data['args']['precision'], NumberValue::class),
            $mode['type'] === 'enum'
                ? constant(RoundingMode::class . '::' . $mode['case'])
                : $mode['value'],
        );
    }
}
