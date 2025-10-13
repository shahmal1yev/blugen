<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\RawSchemaAccessorTrait;
use Blugen\Service\Lexicon\V1\Traits\SupportSchemaTrait;

abstract class IOAbstract
{
    use ArrayableTrait;
    use RawSchemaAccessorTrait;
    use SupportSchemaTrait;

    public function __construct(private readonly SchemaInterface $schema)
    {
    }

    public function __get(string $name): mixed
    {
        return $this->schema->__get($name);
    }

    public function description(): ?string
    {
        return $this->__get('description');
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
