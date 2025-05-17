<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support;

use ArrayIterator;
use Blugen\Enum\SupportTypeEnum;
use Blugen\Service\Lexicon\SchemaInterface;

class ErrorsSchema implements SchemaInterface, \IteratorAggregate, \Countable
{
    private array $errors;

    public function __construct(
        private readonly SchemaInterface $schema,
    )
    {
        $this->errors = [];

        $rawErrors = $this->schema->__get('errors') ?? [];

        foreach ($rawErrors as $error) {
            $this->errors[] = $error;
        }
    }

    public function type(): string
    {
        return SupportTypeEnum::ERRORS->value;
    }

    public function description(): ?string
    {
        return null;
    }

    public function __get(string $name): mixed
    {
        return $this->schema->__get($name);
    }

    /**
     * @return ArrayIterator<array{name: string, description?: string}>
     */
    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->errors);
    }

    public function count(): int
    {
        return count($this->errors);
    }
}
