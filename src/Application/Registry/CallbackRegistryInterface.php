<?php

declare(strict_types=1);

namespace PhpArchitecture\LazyOperators\Application\Registry;

use PhpArchitecture\LazyOperators\Application\Registry\Entry\CallbackDetails;

interface CallbackRegistryInterface
{
    /**
     * @return string[]
     */
    public function names(): array;

    /**
     * Reflects the callback registered under $name. This is deliberately a separate, explicit
     * method rather than something computed eagerly at register() time or bundled into names() —
     * callers (typically a UI) opt into the reflection cost only when they actually want the
     * signature/parameter details.
     */
    public function getCallbackDetails(string $name): CallbackDetails;
}
