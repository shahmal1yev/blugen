<?php

namespace Blugen\Service\Lexicon\V1\Traits;

trait DefaultSchemaTrait
{
    public function type(): string
    {
        return $this->schema->type();
    }

    public function description(): ?string
    {
        return $this->schema->description();
    }

    public function __get(string $name): mixed
    {
        return $this->schema->__get($name);
    }
}
