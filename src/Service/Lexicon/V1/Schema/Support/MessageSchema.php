<?php

namespace Blugen\Service\Lexicon\V1\Schema\Support;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Schema\Meta\UnionSchema;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\RawSchemaAccessorTrait;
use Blugen\Service\Lexicon\V1\Traits\SupportSchemaTrait;

class MessageSchema implements SchemaInterface
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

    public function schema(): UnionSchema
    {
        return new UnionSchema(new Schema($this->__get('schema')));
    }

    public function toArray(): array
    {
        return $this->schema->toArray();
    }
}
