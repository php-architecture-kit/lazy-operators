<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Foundation\Registry\Entry;

use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Description;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Formula;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Group;
use PhpArchitecture\LazyOperators\Foundation\Expression\Meta\Attribute\Name;

readonly class ExpressionAttributes
{
    public function __construct(
        public ?Name $name,
        public ?Formula $formula,
        public ?Description $description,
        public ?Group $group,
    ) {}
}
