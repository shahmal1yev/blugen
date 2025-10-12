<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\SchemaTrait;

abstract class IOAbstract
{
    use ArrayableTrait;
    use SchemaTrait;

    public function __construct(private readonly SchemaInterface $schema)
    {
    }

    abstract public function type(): string;

    public function description(): ?string
    {
        return $this->schema->description();
    }

    public function __get(string $name): mixed
    {
        return $this->schema->__get($name);
    }

    public function encoding(): string
    {
        return $this->__get('encoding');
    }

    public function schema(): array
    {
        return $this->__get('schema');
    }

    public function parameters(): ?array
    {
        return $this->__get('parameters');
    }

    public function toArray(): array
    {
        return $this->schema->toArray();
    }
}
