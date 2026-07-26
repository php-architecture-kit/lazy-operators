<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Infrastructure\Persistence\Serializer\Conditional;

use PhpArchitecture\LazyOperators\Foundation\Conditional\CaseOfSwitchCase;
use PhpArchitecture\LazyOperators\Infrastructure\Persistence\ExpressionSerializerRegistry;

class CaseOfSwitchCaseSerializer
{
    /**
     * @return array{condition: array, value: array}
     */
    public function serialize(CaseOfSwitchCase $case, ExpressionSerializerRegistry $registry): array
    {
        return [
            'condition' => $registry->serialize($case->condition),
            'value' => $registry->serialize($case->value),
        ];
    }

    /**
     * @param array{condition: array, value: array} $data
     */
    public function deserialize(array $data, ExpressionSerializerRegistry $registry): CaseOfSwitchCase
    {
        return new CaseOfSwitchCase(
            $registry->deserialize($data['condition']),
            $registry->deserialize($data['value']),
        );
    }
}
