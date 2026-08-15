<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;

/**
 * A Decorator that carries an ordinary constructor-injected dependency alongside the node it
 * wraps — here a "channel" name, standing in for anything a real decorator would need handed to
 * it (a PSR-3 logger, a cache pool, a metrics client, a sampling rate).
 *
 * Everything on this class is instrumentation for the tests that drive it: $channels records the
 * channel each *evaluated* node actually ran with, and the two counters record how many times the
 * class was constructed versus evaluated.
 */
final class ChannelDecorator implements Decorator
{
    public const DEFAULT_CHANNEL = 'default';

    /** @var array<int, string> */
    public static array $channels = [];
    public static int $constructions = 0;
    public static int $invocations = 0;

    public function __construct(
        private readonly Expression $inner,
        private readonly string $channel = self::DEFAULT_CHANNEL,
    ) {
        ++self::$constructions;
    }

    public function __invoke(): mixed
    {
        ++self::$invocations;
        self::$channels[] = $this->channel;

        return ($this->inner)();
    }

    public static function reset(): void
    {
        self::$channels = [];
        self::$constructions = 0;
        self::$invocations = 0;
    }
}
