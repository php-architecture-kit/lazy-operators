<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;

/**
 * Same idea as ChannelDecorator, except the injected dependency has no default — the decorator
 * genuinely cannot do its job without it, so its constructor requires it. Nothing in the
 * `Decorator` interface forbids this: an interface cannot declare a constructor, so "the
 * constructor must accept exactly one Expression" is a convention DecoratesNodes relies on but
 * no type can express.
 */
final class RequiredTagDecorator implements Decorator
{
    /** @var array<int, string> */
    public static array $tags = [];

    public function __construct(
        private readonly Expression $inner,
        private readonly string $tag,
    ) {
    }

    public function __invoke(): mixed
    {
        self::$tags[] = $this->tag;

        return ($this->inner)();
    }

    public function unwrap(): Expression
    {
        return $this->inner;
    }

    public static function reset(): void
    {
        self::$tags = [];
    }
}
