<?php

namespace Blugen\Service\Lexicon\V1\Schema\Concrete;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\RawSchemaAccessorTrait;

class BooleanSchema implements SchemaInterface
{
    use ArrayableTrait;
    use RawSchemaAccessorTrait;

    public function __construct(private readonly SchemaInterface $schema)
    {}

    public function __get(string $name): mixed
    {
        return $this->schema->__get($name);
    }

    public function type(): string
    {
        return $this->schema->type();
    }

    public function description(): ?string
    {
        return $this->schema->description() ?? null;
    }

    public function default(): ?bool
    {
        return $this->__get('default');
    }

    public function const(): ?bool
    {
        return $this->__get('const');
    }
}
