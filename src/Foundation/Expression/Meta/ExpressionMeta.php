<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Expression\Meta;

use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Exception\MissingMetaAttributeException;
use ReflectionClass;

final class ExpressionMeta
{
    /**
     * @param class-string $class
     */
    public static function name(string $class): string
    {
        return self::read($class, Name::class);
    }

    /**
     * @param class-string $class
     */
    public static function formula(string $class): string
    {
        return self::read($class, Formula::class);
    }

    /**
     * @param class-string $class
     */
    public static function description(string $class): string
    {
        return self::read($class, Description::class);
    }

    /**
     * @param class-string $class
     * @param class-string<Name|Formula|Description> $attributeClass
     */
    private static function read(string $class, string $attributeClass): string
    {
        $attributes = (new ReflectionClass($class))->getAttributes($attributeClass);

        if ($attributes === []) {
            throw MissingMetaAttributeException::create($class, $attributeClass);
        }

        return $attributes[0]->newInstance()->value;
    }
}
