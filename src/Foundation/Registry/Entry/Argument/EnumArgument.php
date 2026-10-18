<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Registry\Entry\Argument;

use PhpArchitecture\LazyOperators\Foundation\Registry\Entry\ExpressionArgument;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use UnitEnum;

readonly class EnumArgument extends ExpressionArgument
{
    /**
     * {@inheritdoc}
     * @param string[] $options
     * @param class-string<UnitEnum> $enumClass
     */
    public function __construct(
        string $name,
        string $type,
        ?string $itemType,
        bool $spread,
        bool $optional,
        ?string $defaultValue,
        ?Description $description,
        public array $options,
        public string $enumClass,
    ) {
        parent::__construct($name, $type, $itemType, $spread, $optional, $defaultValue, $description);
    }
}
