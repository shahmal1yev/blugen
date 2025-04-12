<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\FieldType;

use Blugen\Service\Lexicon\SchemaInterface;

class StringSchema implements SchemaInterface
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

    public function format(): ?string
    {
        return $this->__get('format') ?? null;
    }

    public function maxLength(): ?int
    {
        return $this->__get('maxLength');
    }

    public function minLength(): ?int
    {
        return $this->__get('minLength');
    }

    public function maxGraphemes(): ?int
    {
        return $this->__get('maxGraphemes');
    }

    public function minGraphemes(): ?int
    {
        return $this->__get('minGraphemes');
    }

    public function knownValues(): ?array
    {
        return $this->__get('knownValues');
    }

    public function enum(): ?array
    {
        return $this->__get('enum');
    }

    public function default(): ?string
    {
        return $this->__get('default');
    }

    public function const(): ?string
    {
        return $this->__get('const');
    }
}
