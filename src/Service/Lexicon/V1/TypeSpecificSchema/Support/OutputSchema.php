<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support;

use Blugen\Enum\SupportTypeEnum;
use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\SchemaTrait as SchemaTrait;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\ObjectSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\RefSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\UnionSchema;

class OutputSchema implements SchemaInterface
{
    use ArrayableTrait;
    use SchemaTrait;

    public function __construct(private readonly SchemaInterface $schema)
    {}

    public function type(): string
    {
        return SupportTypeEnum::OUTPUT->value;
    }

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
        return $this->__get('schema') ?? [];
    }
}
