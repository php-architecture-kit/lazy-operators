<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Application\Registry\Entry;

use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Meta\Attribute\Name;

readonly class ExpressionAttributes
{
    public function __construct(
        public ?Name $name,
        public ?Formula $formula,
        public ?Description $description,
    ) {}
}
