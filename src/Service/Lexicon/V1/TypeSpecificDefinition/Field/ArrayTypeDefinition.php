<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Field;

use Blugen\Service\Lexicon\DefinitionInterface;

class ArrayTypeDefinition implements DefinitionInterface
{
    public function __construct(
        private readonly DefinitionInterface $definition
    ) {}

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
        return $this->definition->description();
    }

    public function lexicon(): \Blugen\Service\Lexicon\LexiconInterface
    {
        return $this->definition->lexicon();
    }

    public function items(): array
    {
        return $this->definition->__get('items') ?? [];
    }

    public function minLength(): ?int
    {
        return $this->definition->__get('minLength');
    }

    public function maxLength(): ?int
    {
        return $this->definition->__get('maxLength');
    }

    public function __get(string $name): mixed
    {
        return $this->definition->__get($name);
    }
}
