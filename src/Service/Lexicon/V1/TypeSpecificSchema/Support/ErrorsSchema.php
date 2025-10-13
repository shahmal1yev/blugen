<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support;

use ArrayIterator;
use BadMethodCallException;
use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\SchemaTrait;
use Blugen\Service\Lexicon\V1\Traits\SupportSchemaTrait;

class ErrorsSchema implements SchemaInterface, \IteratorAggregate, \Countable, \ArrayAccess
{
    use ArrayableTrait;
    use SchemaTrait;
    use SupportSchemaTrait;

    /** @var ErrorSchema[] */
    private readonly array $errors;

    public function __construct(
        private readonly SchemaInterface $schema,
    )
    {
        $this->errors = array_map(
            fn(array $content) => new ErrorSchema(new Schema($content)),
            $this->toArray()
        );
    }

    public function __get(string $name): mixed
    {
        return $this->schema->__get($name);
    }

    /** @return ArrayIterator<int, ErrorSchema> */
    public function getIterator(): \ArrayIterator
    {
        return new ArrayIterator($this->errors);
    }

    public function count(): int
    {
        return count($this->errors);
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->errors[$offset]);
    }

    public function offsetGet(mixed $offset): ErrorSchema
    {
        if (!array_key_exists($offset, $this->errors)) {
            throw new \OutOfBoundsException("Error schema offset '{$offset}' not found");
        }

        return $this->errors[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new BadMethodCallException('Schema is read-only');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new BadMethodCallException('Schema is read-only');
    }
}
