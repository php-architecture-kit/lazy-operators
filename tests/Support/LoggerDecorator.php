<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Tests\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression\Decorator;
use PhpArchitecture\LazyOperators\Foundation\Expression\Expression;
use Psr\Log\LoggerInterface;

/**
 * The most naive Decorator one could write: logs every node it wraps to a PSR-3 logger, one
 * DEBUG record per evaluated stage, in evaluation order — no batching, no sampling, no filtering.
 *
 * The logger arrives through the constructor, alongside the node. While the prototype form
 * existed this was impossible — decorate() rebuilt the configured decorator per node with the
 * node as its only argument, so a real logger could only be reached through a static handle set
 * once per test.
 */
final class LoggerDecorator implements Decorator
{
    public function __construct(
        private readonly Expression $inner,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(): mixed
    {
        $result = ($this->inner)();

        $this->logger->debug('lazy_operators.stage.evaluated', [
            'node' => $this->inner::class,
            'result' => $result,
        ]);

        return $result;
    }
}
