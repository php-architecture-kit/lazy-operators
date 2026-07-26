<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Conditional\CaseOfSwitchCase;
use PhpArchitecture\LazyOperators\Foundation\Conditional\SwitchCaseOperator;
use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializer;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class SwitchCaseOperatorSerializer implements ExpressionSerializer
{
    public function __construct(
        private readonly CaseOfSwitchCaseSerializer $cases = new CaseOfSwitchCaseSerializer(),
    ) {
    }

    public function supports(Expression $expression): bool
    {
        return $expression instanceof SwitchCaseOperator;
    }

    public function expressionVersion(): string
    {
        return SwitchCaseOperator::VERSION;
    }

    /**
     * @return array{uid: string, key: string, class: string, version: string, args: array<mixed>}
     */
    public function serialize(Expression $expression, ExpressionSerializerRegistry $registry): array
    {
        assert($expression instanceof SwitchCaseOperator);

        return [
            'uid' => SwitchCaseOperator::UID,
            'key' => SwitchCaseOperator::KEY,
            'class' => SwitchCaseOperator::class,
            'version' => SwitchCaseOperator::VERSION,
            'args' => [
                'subject' => $registry->serialize($expression->condition),
                'cases' => array_map(
                    fn (CaseOfSwitchCase $case) => $this->cases->serialize($case, $registry),
                    $expression->cases,
                ),
                'default' => $expression->default === null ? null : $registry->serialize($expression->default),
            ],
        ];
    }

    /**
     * @param array{uid: string, key: string, class: string, version: string, args: array<mixed>} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): Expression
    {
        return new SwitchCaseOperator(
            $registry->deserialize($data['args']['subject']),
            array_map(
                fn (mixed $case) => $this->cases->deserialize($case, $registry),
                $data['args']['cases'],
            ),
            $data['args']['default'] === null ? null : $registry->deserialize($data['args']['default']),
        );
    }
}
