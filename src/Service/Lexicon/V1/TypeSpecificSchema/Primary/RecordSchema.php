<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Primary;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\Traits\ArrayableTrait;
use Blugen\Service\Lexicon\V1\Traits\DefaultSchemaTrait;
use Blugen\Service\Lexicon\V1\Traits\SchemaTrait;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\ObjectSchema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Field\StringSchema;

class RecordSchema implements SchemaInterface
{
    use SchemaTrait;
    use DefaultSchemaTrait;
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
