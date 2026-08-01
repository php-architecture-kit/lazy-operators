<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Extension\Array;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\Type\ArrayValue;
use PhpArchitecture\LazyOperators\Foundation\Type\StringValue;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

#[Group('Array')]
#[Name('Get')]
#[Formula('f(array, path) = value at the dot-separated path within array, or null if any segment is missing')]
#[Description('Get walks a dot-separated path (e.g. "user.address.city") through a nested array and returns the value found there, or null when the path does not resolve.')]
class ArrayGetFunction implements Expression
{
    public const KEY = 'array_get';
    public const UID = '3ce9c9c7-6581-4d44-8e1e-fce62e5c99cb';
    public const VERSION = '1.0';

    /**
     * @param ArrayValue<mixed> $array
     */
    public function __construct(
        public readonly ArrayValue $array,
        public readonly StringValue $path,
    ) {}

    public function __invoke(): mixed
    {
        $current = ($this->array)();

        foreach (explode('.', ($this->path)()) as $segment) {
            if (!is_array($current) || !array_key_exists($segment, $current)) {
                return null;
            }

            $current = $current[$segment];
        }

        return $current;
    }
}
