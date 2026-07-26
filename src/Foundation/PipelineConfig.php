<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation;

class PipelineConfig
{
    public function __construct(
        public readonly ?Expression $decorator = null,
    ) {
    }
}
