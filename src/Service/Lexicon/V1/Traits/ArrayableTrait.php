<?php

namespace Blugen\Service\Lexicon\V1\Traits;

trait ArrayableTrait
{
    public function toArray(): array
    {
        return array_filter($this->schema(), static fn ($value) => $value !== null);
    }
}
