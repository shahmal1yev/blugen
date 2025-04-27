<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field;

use Blugen\Service\Lexicon\SchemaInterface;

class TokenSchema implements SchemaInterface
{
    public function __construct(
        private readonly SchemaInterface $schema
    ) {}

    public function type(): string
    {
        return 'token';
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
