<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificDefinition\Primary;

use Blugen\Service\Lexicon\DefinitionInterface;
use Blugen\Service\Lexicon\LexiconInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\ParamsSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support\ErrorsSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support\MessageSchema;

class SubscriptionTypeDefinition implements DefinitionInterface
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

    public function parameters(): ?ParamsSchema
    {
        /** @var ?array $params */
        $params = $this->__get('parameters');

        if ($params === null) {
            return null;
        }

        return new ParamsSchema(new Schema($params));
    }

    public function errors(): ?ErrorsSchema
    {
        /** @var ?array $errors */
        $errors = $this->__get('errors');

        if ($errors === null) {
            return null;
        }

        return new ErrorsSchema(new Schema($errors));
    }

    public function message(): ?MessageSchema
    {
        /** @var ?array $message */
        $message = $this->__get('message');

        if ($message === null) {
            return null;
        }

        return new MessageSchema(new Schema($message));
    }
}
