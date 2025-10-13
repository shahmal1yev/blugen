<?php

namespace Blugen\Service\Lexicon\V1\Schema\Meta;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\RawSchemaAccessorTrait;

class RefSchema implements SchemaInterface
{
    use ArrayableTrait;
    use RawSchemaAccessorTrait;

    public function __construct(
        private readonly SchemaInterface $schema,
    )
    {}

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

    public function ref(): string
    {
        return $this->__get('ref');
    }
}
