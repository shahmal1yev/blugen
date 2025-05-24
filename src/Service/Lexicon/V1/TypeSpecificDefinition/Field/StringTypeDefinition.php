<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Field;

use Blugen\Service\Lexicon\DefinitionInterface;
use Blugen\Service\Lexicon\LexiconInterface;
use Blugen\Service\Lexicon\ProcedureInterface;

class StringTypeDefinition implements DefinitionInterface
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
        return $this->definition->description();
    }

    public function lexicon(): LexiconInterface
    {
        return $this->definition->lexicon();
    }

    public function __get(string $name): mixed
    {
        return $this->definition->__get($name);
    }

    public function knownValues(): array
    {
        return $this->__get('knownValues') ?? throw new \LogicException(
            "'knownValues' is not available for use as cases in enum generation"
        );
    }
}
