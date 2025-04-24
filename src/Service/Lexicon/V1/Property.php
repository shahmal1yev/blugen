<?php

namespace Blugen\Service\Lexicon\V1;

use Blugen\Service\Lexicon\SchemaInterface;

class Property
{
    public function __construct(
        private readonly string $name,
        private readonly SchemaInterface $schema,
        private readonly bool $nullable = false,
        private readonly bool $required = true
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function schema(): SchemaInterface
    {
        return $this->schema;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }

    public function description(): ?string
    {
        return $this->schema()->description();
    }
}
