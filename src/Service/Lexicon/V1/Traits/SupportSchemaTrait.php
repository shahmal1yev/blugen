<?php

namespace Blugen\Service\Lexicon\V1\Traits;

use BadMethodCallException;

trait SupportSchemaTrait
{
    public function type(): string
    {
        throw new BadMethodCallException(sprintf(
            '%s::type() is not supported for support schemas.',
            static::class
        ));
    }

    public function description(): string
    {
        throw new BadMethodCallException(sprintf(
            '%s::description() is not supported for support schemas.',
            static::class
        ));
    }
}
