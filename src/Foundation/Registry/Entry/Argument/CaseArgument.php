<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Application\Registry\Entry\Argument;

use PhpArchitecture\LazyOperators\Application\Registry\Entry\ExpressionArgument;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;

readonly class CaseArgument extends ExpressionArgument
{
    /**
     * {@inheritdoc}
     * @param ExpressionArgument[] $fields
     */
    public function __construct(
        string $name,
        string $type,
        ?string $itemType,
        bool $spread,
        bool $optional,
        ?string $defaultValue,
        ?Description $description,
        public array $fields,
    ) {
        parent::__construct($name, $type, $itemType, $spread, $optional, $defaultValue, $description);
    }
}
