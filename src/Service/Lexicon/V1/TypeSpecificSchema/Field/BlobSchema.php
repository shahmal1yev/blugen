<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\RawSchemaAccessorTrait;

class BlobSchema implements SchemaInterface
{
    use ArrayableTrait;
    use RawSchemaAccessorTrait;

    public function __construct(
        private readonly SchemaInterface $schema
    ) {}

    public function type(): string
    {
        return 'blob';
    }

    public function description(): ?string
    {
        return $this->schema->description();
    }

    public function accept(): ?array
    {
        return $this->__get('accept') ?? null;
    }

    public function maxSize(): ?int
    {
        return $this->__get('maxSize') ?? null;
    }

    public function __get(string $name): mixed
    {
        return $this->schema->__get($name);
    }
}
