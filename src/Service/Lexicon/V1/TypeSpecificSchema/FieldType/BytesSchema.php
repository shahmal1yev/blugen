<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\FieldType;

use Blugen\Service\Lexicon\SchemaInterface;

class BytesSchema implements SchemaInterface
{
    public function __construct(
        private readonly SchemaInterface $schema,
    )
    {
    }

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

    public function minLength(): ?int
    {
        return $this->__get('minLength');
    }

    public function maxLength(): ?int
    {
        return $this->__get('maxLength');
    }
}
