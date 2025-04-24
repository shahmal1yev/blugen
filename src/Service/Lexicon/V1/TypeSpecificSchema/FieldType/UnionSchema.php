<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\FieldType;

use Blugen\Service\Lexicon\SchemaInterface;

class UnionSchema implements SchemaInterface
{
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

    /**
     * @return string[]
     */
    public function refs(): array
    {
        return $this->__get('refs') ?? [];
    }

    public function closed(): bool
    {
        return (bool) ($this->__get('closed') ?? false);
    }
}
