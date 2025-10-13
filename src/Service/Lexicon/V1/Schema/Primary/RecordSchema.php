<?php

namespace Blugen\Service\Lexicon\V1\Schema\Primary;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Schema\Container\ObjectSchema;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\RawSchemaAccessorTrait;
use Blugen\Service\Lexicon\V1\Traits\SchemaTrait;

class RecordSchema implements SchemaInterface
{
    use RawSchemaAccessorTrait;
    use SchemaTrait;
    use ArrayableTrait;

    public function __construct(private readonly SchemaInterface $schema)
    {
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
