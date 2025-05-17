<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary;

use Blugen\Service\Lexicon\DefinitionInterface;
use Blugen\Service\Lexicon\LexiconInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\ParamsSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support\ErrorsSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support\OutputSchema;

class QueryTypeDefinition implements DefinitionInterface
{
    public function __construct(private readonly DefinitionInterface $definition)
    {}

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

    public function parameters(): ?ParamsSchema
    {
        $parameters = $this->__get('parameters');

        if ($parameters === null) {
            return null;
        }

        return new ParamsSchema(new Schema($parameters));
    }

    public function output(): ?OutputSchema
    {
        $output = $this->__get('output');

        if ($output === null) {
            return null;
        }

        return new OutputSchema(new Schema($output));
    }

    public function errors(): ErrorsSchema
    {
        $errors = $this->__get('errors');

        if (! is_array($errors)) {
            $errors = [];
        }

        return new ErrorsSchema(new Schema($errors));
    }
}
