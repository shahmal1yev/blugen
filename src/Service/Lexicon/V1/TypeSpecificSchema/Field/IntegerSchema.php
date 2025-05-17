<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field;

use Blugen\Service\Lexicon\SchemaInterface;

class IntegerSchema implements SchemaInterface
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

    public function minimum(): ?int
    {
        return $this->__get('minimum');
    }

    public function maximum(): ?int
    {
        return $this->__get('maximum');
    }

    public function enum(): ?array
    {
        return $this->__get('enum');
    }

    public function default(): ?int
    {
        return $this->__get('default');
    }

    public function const(): ?int
    {
        return $this->__get('const');
    }
}
