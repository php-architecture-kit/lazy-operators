<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Support;

use PhpArchitecture\LazyOperators\Foundation\Expression;
use PhpArchitecture\LazyOperators\Foundation\PipelineConfig;

trait DecoratesNodes
{
    private static function decorate(Expression $node, PipelineConfig $config): Expression
    {
        if ($config->decorator === null) {
            return $node;
        }

        // $decorator is a prototype: its class is reinstantiated per node, so its
        // constructor must accept a single Expression (the node being wrapped).
        return new ($config->decorator::class)($node);
    }
}
