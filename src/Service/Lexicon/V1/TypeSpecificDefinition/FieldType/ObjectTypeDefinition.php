<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificDefinition\FieldType;

use Blugen\Service\Lexicon\DefinitionInterface;
use Blugen\Service\Lexicon\LexiconInterface;

class ObjectTypeDefinition implements DefinitionInterface
{
    public function __construct(private readonly DefinitionInterface $definition)
    {
    }

    public function name(): string
    {
        return $this->definition->name();
    }

    public function type(): string
    {
        return $this->definition->type();
    }

    public function description(): ?string
    {
        return $this->definition->description() ?? null;
    }

    public function lexicon(): LexiconInterface
    {
        return $this->definition->lexicon();
    }

    public function __get(string $name): mixed
    {
        return $this->definition->__get($name);
    }

    public function properties(): array
    {
        return $this->__get('properties');
    }

    public function required(): ?array
    {
        return $this->__get('required');
    }

    public function nullable(): ?array
    {
        return $this->__get("nullable");
    }
}
