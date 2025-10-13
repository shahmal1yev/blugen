<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary;

use Blugen\Service\Lexicon\DefinitionInterface;
use Blugen\Service\Lexicon\LexiconInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Schema\Field\ObjectSchema;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\DefinitionTrait as DefinitionTrait;

class RecordTypeDefinition implements DefinitionInterface
{
    use ArrayableTrait;
    use DefinitionTrait;

    public function __construct(
        private readonly DefinitionInterface $definition
    )
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

    public function key(): string
    {
        return $this->__get('key');
    }

    public function record(): ObjectSchema
    {
        return new ObjectSchema(new Schema($this->__get('record')));
    }
}
