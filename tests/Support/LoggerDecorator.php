<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * The most naive Decorator one could write: logs every node it wraps to a PSR-3 logger, one
 * DEBUG record per evaluated stage, in evaluation order — no batching, no sampling, no filtering.
 * decorate() re-instantiates the configured decorator per node (`new ($config->decorator::class)($node)`),
 * so the only way to reach a real logger instance from inside is a static handle set up once per
 * test via useLogger(), the same constraint RecordingExpression works around with a static log array.
 */
final class LoggerDecorator implements Decorator
{
    private static LoggerInterface $logger;

    public function __construct(
        private readonly Expression $inner,
    ) {
    }

    public static function useLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    public static function reset(): void
    {
        self::$logger = new NullLogger();
    }

    public function __invoke(): mixed
    {
        $result = ($this->inner)();

        self::$logger->debug('lazy_operators.stage.evaluated', [
            'node' => $this->inner::class,
            'result' => $result,
        ]);

        return $result;
    }

}
