<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Conditional\Exception;

use LogicException;

final class IncompleteIfBuilderException extends LogicException implements LazyOperatorsConditionalException
{
    public static function create(bool $missingThen, bool $missingElse): self
    {
        $missing = match (true) {
            $missingThen && $missingElse => 'then() and else()',
            $missingThen => 'then()',
            default => 'else()',
        };

        return new self(sprintf('IfBuilder requires %s to be set before build().', $missing));
    }
}
