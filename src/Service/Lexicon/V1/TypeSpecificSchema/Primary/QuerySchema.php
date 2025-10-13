<?php

namespace Blugen\Service\Lexicon\V1\TypeSpecificSchema\Primary;

use Blugen\Service\Lexicon\SchemaInterface;
use Blugen\Service\Lexicon\V1\Schema;
use Blugen\Service\Lexicon\V1\TypeSpecificSchema\Support\OutputSchema;

class QuerySchema extends HTTPAbstract implements SchemaInterface
{
    public function output(): ?OutputSchema
    {
        if (is_null($schemaContent = $this->__get('output'))) {
            return null;
        }

        return new OutputSchema(new Schema($schemaContent));
    }
}
